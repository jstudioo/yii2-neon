<?php

namespace lambda\neon\models;

use app\helpers\MainHelper;
use Yii;
use yii\helpers\Html;
use yii\imagine\Image;

/**
 * This is the model class for table "app_file".
 *
 * @property string $id
 * @property string $originalName
 * @property string $fileName
 * @property string $thumbnailFileName
 * @property string $pathAlias
 * @property string $fileUri
 * @property double $fileSize
 * @property string $fileExtension
 * @property string $mimeType
 * @property string $additionalInformation
 * @property integer $isDeleted
 * @property string $createdAt
 * @property integer $createdBy
 * @property string $updatedAt
 * @property integer $updatedBy
 * @property string $deletedAt
 * @property integer $deletedBy
 */
class AppFile extends Base {

  const EXT_ALL = 1;
  const EXT_IMAGE = 2;
  const EXT_DOCUMENT = 3;
  const EXT_AUDIO = 4;
  const EXT_VIDEO = 5;

  public $thumbExtra = '_thumbnail';
  public $thumbnailExtension = 'jpg';
  public static $allowedDocumentExtensions = ['txt', 'pdf', 'doc', 'docx'];
  public static $allowedImageExtensions = ['jpg', 'jpeg', 'png'];
  public static $allowedVideoExtensions = ['mp4', 'wmv', 'mpg', 'mpeg'];
  public static $allowedAudioExtensions = ['mp3'];
  public static $uploadPath = 'uploads';
  public static $uploadAlias = '@app';

  /**
   * @inheritdoc
   */
  public static function tableName() {
    return 'app_file';
  }

  /**
   * @inheritdoc
   */
  public function rules() {
    return [
      [['originalName', 'fileName', 'pathAlias', 'fileSize', 'fileExtension', 'mimeType'], 'required'],
      [['fileUri', 'additionalInformation'], 'string'],
      [['fileSize'], 'number'],
      [['createdAt', 'updatedAt', 'deletedAt'], 'safe'],
      [['isDeleted', 'createdBy', 'updatedBy', 'deletedBy'], 'integer'],
      [['originalName', 'fileName', 'thumbnailFileName', 'pathAlias', 'fileExtension', 'mimeType'], 'string', 'max' => 255],
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels() {
    return [
      'id' => Yii::t('app', 'ID'),
      'originalName' => Yii::t('app', 'Original Name'),
      'fileName' => Yii::t('app', 'File Name'),
      'pathAlias' => Yii::t('app', 'Path Alias'),
      'fileUri' => Yii::t('app', 'File Uri'),
      'fileSize' => Yii::t('app', 'File Size'),
      'fileExtension' => Yii::t('app', 'File Extension'),
      'mimeType' => Yii::t('app', 'Mime Type'),
      'additionalInformation' => Yii::t('app', 'Additional Information'),
      'isDeleted' => Yii::t('app', 'Is Deleted'),
      'createdAt' => Yii::t('app', 'Created At'),
      'createdBy' => Yii::t('app', 'Created By'),
      'updatedAt' => Yii::t('app', 'Updated At'),
      'updatedBy' => Yii::t('app', 'Updated By'),
      'deletedAt' => Yii::t('app', 'Deleted At'),
      'deletedBy' => Yii::t('app', 'Deleted By'),
    ];
  }

  public static function getAllowedExtensions() {
    return array_merge(static::$allowedImageExtensions, static::$allowedDocumentExtensions, static::$allowedAudioExtensions, static::$allowedVideoExtensions);
  }

  public static function getAllowedDocumentExtensions() {
    return static::$allowedDocumentExtensions;
  }

  public static function getAllowedImageExtensions() {
    return static::$allowedImageExtensions;
  }

  public static function getAllowedAudioExtensions() {
    return static::$allowedAudioExtensions;
  }

  public static function getAllowedVideoExtensions() {
    return static::$allowedVideoExtensions;
  }

  private static function generateFileName() {
    if (\Yii::$app->user->isGuest) {
      return '0_' . date('YmdHis') . '_' . \Yii::$app->security->generateRandomString(5);
    } else {
      return Yii::$app->user->id . '_' . date('YmdHis') . '_' . \Yii::$app->security->generateRandomString(5);
    }
  }

  public static function upload($fileInstance, $mode = 1, $additionalPath = FALSE, $createThumbnail = FALSE) {

    $fileOriginalName = $fileInstance->name;
    $fileSize = $fileInstance->size;
    $fileExtension = $fileInstance->extension;
    $fileType = $fileInstance->type;

    $fileName = self::generateFileName();

    if ($additionalPath) {
      $dirPath = \Yii::getAlias(self::$uploadAlias) . "/web/" . self::$uploadPath . '/' . $additionalPath . '/';
    } else {
      $dirPath = \Yii::getAlias(self::$uploadAlias) . "/web/" . self::$uploadPath . '/';
    }

    $fullPath = $dirPath . $fileName . '.' . $fileInstance->extension;

    MainHelper::makeDir($dirPath);

    $appFile = new AppFile();
    $appFile->pathAlias = self::$uploadAlias;
    $appFile->originalName = $fileOriginalName;
    $appFile->fileName = $fileName;
    $appFile->fileSize = $fileSize;
    $appFile->fileExtension = $fileExtension;
    $appFile->mimeType = $fileType;

    if ($additionalPath) {
      $appFile->fileUri = self::$uploadPath . '/' . $additionalPath . '/';
    } else {
      $appFile->fileUri = self::$uploadPath . '/';
    }

    $extensionHaystack = [];
    switch ($mode) {
      case self::EXT_IMAGE:
        $extensionHaystack = self::getAllowedImageExtensions();
        break;
      case self::EXT_DOCUMENT:
        $extensionHaystack = self::getAllowedDocumentExtensions();
        break;
      case self::EXT_AUDIO:
        $extensionHaystack = self::getAllowedAudioExtensions();
        break;
      case self::EXT_VIDEO:
        $extensionHaystack = self::getAllowedVideoExtensions();
        break;
      default:
        $extensionHaystack = self::getAllowedExtensions();
        break;
    }

    if ($appFile->validate() && in_array($fileExtension, $extensionHaystack) && $fileInstance->saveAs($fullPath) && $appFile->save()) {
      if ($createThumbnail) {
        $appFile->createThumbnail();
      }
      return $appFile;
    } else {
      return FALSE;
    }
  }

  public static function uploads($fileInstances, $mode, $additionalPath = FALSE, $createThumbnail = FALSE) {
    $appFiles = [];
    foreach ($fileInstances as $fileInstance) {
      $appFiles[] = self::upload($fileInstance, $mode, $additionalPath, $createThumbnail);
    }
    return $appFiles;
  }

  public function createThumbnail($thumbnailName = FALSE, $captureInterval = 10, $width = '320', $height = '240', $quality = 100) {

    if (!Yii::$app->user->isGuest) {
      $thumbName = $thumbnailName ? : time() . '_' . $this->getExtras();
      $thumbFileName = $thumbName . '.' . $this->thumbnailExtension;
      $thumbFileLocation = Yii::getAlias($this->pathAlias) . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . $this->fileUri . $thumbFileName;
      $isSuccess = FALSE;
      if (in_array($this->fileExtension, static::$allowedVideoExtensions)) {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
          $ffmpeg = 'ffmpeg.exe';
        } else {
          $ffmpeg = 'ffmpeg';
        }

        $videoSource = $this->source;

        $thumbnailSize = $width . 'x' . $height;

        $cmd = "$ffmpeg -i $videoSource -deinterlace -an -ss $captureInterval -f mjpeg -t 1 -r 1 -y -s $thumbnailSize $thumbFileLocation 2>&1";
        $creationSuccess = exec($cmd);
      } elseif (in_array($this->fileExtension, static::$allowedImageExtensions)) {
        $creationSuccess = Image::thumbnail($this->fullpath, $width, $height)
            ->save($thumbFileLocation, ['quality' => $quality]);
      } else {
        $isSuccess = TRUE;
      }

      if ($creationSuccess) {
        $this->thumbnailFileName = $thumbName;
      }

      return $this->save();
    } else {
      return FALSE;
    }
  }

  public function clearDelete() {
    $fileRemoved = FALSE;

    if (file_exists($this->fullpath)) {
      unlink($this->fullpath);
      if ($this->thumbnailFileName && file_exists($this->fullpathThumbnail)) {
        unlink($this->fullpathThumbnail);
      }

      $fileRemoved |= TRUE;
    }


    return $fileRemoved ? $this->delete() : FALSE;
  }

  public function getFullpath() {
    return Yii::getAlias($this->pathAlias) . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . $this->fileUri . $this->fileName . '.' . $this->fileExtension;
  }

  public function getThumbnailFullpath() {
    return Yii::getAlias($this->pathAlias) . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . $this->fileUri . $this->thumbnailFileName . '.' . $this->fileExtension;
  }

  public function getSource() {
    return '/' . $this->fileUri . $this->fileName . '.' . $this->fileExtension;
  }

  public function getThumbnailSource() {
    return $this->thumbnailFileName ? '/' . $this->fileUri . $this->thumbnailFileName . '.' . $this->fileExtension : FALSE;
  }

  public function getAsImage() {
    return Html::img($this->pathAlias . DIRECTORY_SEPARATOR . $this->fileUri . $this->fileName . '.' . $this->fileExtension);
  }

  public function getThumbnailAsImage() {
    return Html::img($this->pathAlias . DIRECTORY_SEPARATOR . $this->fileUri . $this->thumbnailFileName . '.' . $this->fileExtension);
  }

}
