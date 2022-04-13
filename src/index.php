<?php
/*
    Kernel::LINEAR
    ----------------------------
    Load time: 88.721944093704
    Train time: 1112.4222550392
    Persist time: 0.14607405662537
    Predict time: 237.22228693962
    Accuracy: 0.9398
    Total time: 1438.5451290607
    Memory: 2.95 gb
*/
include '../vendor/autoload.php';
include 'MnistDataset.php';

define('EOL', chr(10) . chr(13));

use App\MnistDataset;
use Phpml\Classification\SVC;
use Phpml\Metric\Accuracy;
use Phpml\ModelManager;
use Phpml\SupportVectorMachine\Kernel;

function convert($size)
{
    $unit = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}

$start = $loadStart = hrtime(true);
echo 'Start the training!' . EOL;

$trainDataset = new MnistDataset('data/train-images.idx3-ubyte', 'data/train-labels.idx1-ubyte');
$testDataset = new MnistDataset('data/t10k-images.idx3-ubyte', 'data/t10k-labels.idx1-ubyte');
echo 'Load time: ' . hrtime(true) - $loadStart . EOL;

$trainStart = hrtime(true);
$classifier = new SVC(Kernel::POLYNOMIAL);
$classifier->train($trainDataset->getSamples(), $trainDataset->getTargets());
echo `Train time: ` . hrtime(true) - $trainStart . EOL;

$modelStart = hrtime(true);
$modelManager = new ModelManager();
$modelManager->saveToFile($classifier, __DIR__ . '/data/mnist-svm-model.phpml');
echo `Persist time: ` . hrtime(true) - $modelStart . EOL;

$predictStart = hrtime(true);
$predicted = $classifier->predict($testDataset->getSamples());
echo `Predict time: ` . hrtime(true) - $predictStart . EOL;

echo `Accuracy: ` . Accuracy::score($testDataset->getTargets(), $predicted) . EOL;
echo `Total time: ` . hrtime(true) - $start . EOL;
echo `Memory: ` . convert(memory_get_peak_usage()) . EOL;
