<?php
include 'include.php';

use App\MnistDataset;
use Phpml\Classification\SVC;
use Phpml\Metric\Accuracy;
use Phpml\ModelManager;
use Phpml\SupportVectorMachine\Kernel;

$start = hrtime(true);
$outputFile = __DIR__ . '/mnist-svm-model.phpml';

if (file_exists($outputFile)) {
    echo 'Loading model from file' . EOL;

    $time = hrtime(true);
    $modelManager = new ModelManager();
    $classifier = $modelManager->restoreFromFile($outputFile);
    echo 'Restore time: ' . stopWatch($time) . EOL;

    $time = hrtime(true);
    $testDataset = new MnistDataset('data/t10k-images.idx3-ubyte', 'data/t10k-labels.idx1-ubyte');
    echo 'Load time test samples: ' . stopWatch($time) . EOL;
} else {
    echo 'Start the training!' . EOL;

    $time = hrtime(true);
    $trainDataset = new MnistDataset('data/train-images.idx3-ubyte', 'data/train-labels.idx1-ubyte');
    $testDataset = new MnistDataset('data/t10k-images.idx3-ubyte', 'data/t10k-labels.idx1-ubyte');
    echo 'Load time: ' . stopWatch($time) . EOL;

    $time = hrtime(true);
    $classifier = new SVC(Kernel::POLYNOMIAL);
    $classifier->train($trainDataset->getSamples(), $trainDataset->getTargets());
    echo 'Train time: ' . stopWatch($time) . EOL;

    $time = hrtime(true);
    $modelManager = new ModelManager();
    $modelManager->saveToFile($classifier, $outputFile);
    echo 'Persist time: ' . stopWatch($time) . EOL;
}

$time = hrtime(true);
$predicted = $classifier->predict($testDataset->getSamples());
echo 'Predict time: ' . stopWatch($time) . EOL . EOL;

echo 'Accuracy: ' . Accuracy::score($testDataset->getTargets(), $predicted) . EOL;
echo 'Total time: ' . stopWatch($start) . EOL;
echo 'Memory: ' . convertBytes(memory_get_peak_usage()) . EOL;