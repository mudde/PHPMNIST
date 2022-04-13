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
define("ROOT_DIR", realpath(__DIR__ . '/..'));

include '../vendor/autoload.php';
include 'MnistDataset.php';

use App\MnistDataset;
use Phpml\Classification\SVC;
use Phpml\Metric\Accuracy;
use Phpml\ModelManager;
use Phpml\SupportVectorMachine\Kernel;

function convert($size)
{
    $unit = ['b', 'kb', 'mb', 'gb', 'tb', 'pb'];
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}

$start = $loadStart = microtime(true);
$trainDataset = new MnistDataset('data/train-images.idx3-ubyte', 'data/train-labels.idx1-ubyte');
$testDataset = new MnistDataset('data/t10k-images.idx3-ubyte', 'data/t10k-labels.idx1-ubyte');
$loadTime = microtime(true) - $loadStart;

echo `Load time: ${$loadTime}${PHP_EOL}`;

$trainStart = microtime(true);
$classifier = new SVC(Kernel::POLYNOMIAL);
$classifier->train($trainDataset->getSamples(), $trainDataset->getTargets());

$trainTime = microtime(true) - $trainStart;
echo `Train time: ${microtime(true) - $trainStart}`;

$modelStart = microtime(true);
$modelManager = new ModelManager();
$modelManager->saveToFile($classifier, __DIR__ . '/data/mnist-svm-model.phpml');
$modelEnd = microtime(true) - $modelStart;
echo `Persist time: ${$modelEnd}${PHP_EOL}`;

$predictStart = microtime(true);
$predicted = $classifier->predict($testDataset->getSamples());
$predictEnd = microtime(true) - $predictStart;
echo `Predict time: ${$predictEnd}${PHP_EOL}${PHP_EOL}`;

$accuracyScore = Accuracy::score($testDataset->getTargets(), $predicted);
echo `Accuracy: ${$accuracyScore}${PHP_EOL}`;
$totalTime = microtime(true) - $start;
echo `Total time: {$totalTime}${PHP_EOL}`;
$memory = convert(memory_get_peak_usage());
echo `Memory: ${$memory}${PHP_EOL}`;