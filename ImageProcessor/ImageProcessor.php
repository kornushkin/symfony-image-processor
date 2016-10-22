<?php

namespace Kornushkin\Bundle\ImageProcessorBundle\ImageProcessor;

use Symfony\Component\Filesystem\Filesystem;
use \AppBundle\Exception\Image\UnknownImageFormatException;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Image processor.
 */
class ImageProcessor
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * Allowed image types (MIME-types).
     *
     * @var array
     */
    private $imageMimeTypes;

    /**
     * @param Filesystem $filesystem
     * @param TranslatorInterface $translator
     * @param array $imageMimeTypes
     */
    public function __construct(Filesystem $filesystem, TranslatorInterface $translator, array $imageMimeTypes)
    {
        $this->filesystem = $filesystem;
        $this->translator = $translator;
        $this->imageMimeTypes = $imageMimeTypes;
    }

    /**
     * Save photo.
     *
     * @param string $fromFilePath Source file path.
     * @param string $photoDirectory
     * @param string $toFilePath   Destination file path.
     * @return null
     */
    public function savePhoto($fromFilePath, $photoDirectory, $toFilePath)
    {
        // Create directory.
        $this->filesystem->mkdir($photoDirectory);

        // Save image.
        $im = new \Imagick($fromFilePath);
        $im->writeImage($toFilePath);
        $im->clear();
        $im->destroy();
    }

    /**
     * Process thumb photo.
     *
     * @param string $fromFilePath Source file path.
     * @param string $photoDirectory
     * @param string $toFilePath   Destination file path.
     * @param int $width
     * @param int $height
     * @return null
     */
    public function saveThumbPhoto($fromFilePath, $photoDirectory, $toFilePath, $width, $height)
    {
        // Create directory.
        $this->filesystem->mkdir($photoDirectory);

        // Crop and save image.
        $im = new \Imagick($fromFilePath);
        $im->cropThumbnailImage($width, $height);
        $im->writeImage($toFilePath);
        $im->clear();
        $im->destroy();
    }

    /**
     * Detect picture extension.
     *
     * @param string $file File path.
     * @return string
     * @throws UnknownImageFormatException
     */
    public function getImageFormat($file)
    {
        $im = new \Imagick();
        $im->readImage($file);
        $format = $im->identifyImage();
        if (isset($format['mimetype'])) {
            foreach ($this->imageMimeTypes as $format => $this->imageMimeTypes) {
                if ($this->imageMimeTypes === $format['mimetype']) {
                    return $format;
                }
            }
        }

        // If format not allowed.
        throw new UnknownImageFormatException($this->translator->trans('app.client.unknown_image_format_only_jpg_and_png_available'));
    }

    /**
     * Resize and save image in new sizes.
     *
     * @param string $fromFilePath Source file path.
     * @param string|null $photoDirectory
     * @param string $toFilePath   Destination file path.
     * @param int $width
     * @param int $height
     * @return null
     */
    public function resizeAndSaveImage($fromFilePath, $photoDirectory = null, $toFilePath, $width, $height)
    {
        // Create directory.
        if ($photoDirectory) {
            $this->filesystem->mkdir($photoDirectory);
        }

        // Resize and save image.
        $im = new \Imagick();
        $im->readImage($fromFilePath);
        $im->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1);
        $im->writeImage($toFilePath);
        $im->clear();
        $im->destroy();
    }

    public function test()
    {
        die('test complete');
    }

    /**
     * Return image sizes array:
     *  [
     *    width:  => xxx,
     *    height: => yyy
     *  ]
     *
     * @param $file
     * @return array
     */
    public function getImageWidthAndHeight($file)
    {
        $im = new \Imagick();
        $im->readImage($file);

        return [
            'width'  => $im->getImageWidth(),
            'height' => $im->getImageHeight()
        ];
    }

    /**
     * Process photo (resize it by width).
     *
     * @param string $fromFilePath Source file path.
     * @param string $photoDirectory
     * @param string $toFilePath   Destination file path.
     * @param int $width
     * @return null
     */
    public function savePhotoResizedByWidth($fromFilePath, $photoDirectory, $toFilePath, $width)
    {
        // Create photo directory.
        $this->filesystem->mkdir($photoDirectory);

        // Resize and save image.
        $im = new \Imagick($fromFilePath);
        $im->resizeImage($width, 0, $im::FILTER_LANCZOS, 1);
        $im->writeImage($toFilePath);
        $im->clear();
        $im->destroy();
    }
}