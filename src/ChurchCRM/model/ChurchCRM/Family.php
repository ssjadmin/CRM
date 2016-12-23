<?php

namespace ChurchCRM;

use ChurchCRM\dto\SystemURLs;
use ChurchCRM\Base\Family as BaseFamily;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'family_fam' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Family extends BaseFamily
{

  function getAddress()
  {

    $address = array();
    if (!empty($this->getAddress1())) {
      $tmp = $this->getAddress1();
      if (!empty($this->getAddress2())) {
        $tmp = $tmp . " " . $this->getAddress2();
      }
      array_push($address, $tmp);
    }

    if (!empty($this->getCity())) {
      array_push($address, $this->getCity() . ",");
    }

    if (!empty($this->getState())) {
      array_push($address, $this->getState());
    }

    if (!empty($this->getZip())) {
      array_push($address, $this->getZip());
    }
    if (!empty($this->getCountry())) {
      array_push($address, $this->getCountry());
    }

    return implode(" ", $address);
  }
  
  function getViewURI()
  {
    return SystemURLs::getRootPath() . "/FamilyView.php?FamilyID=" . $this->getId();
  } 

  function getWeddingDay()
  {
    if (!is_null($this->getWeddingdate()) && $this->getWeddingdate() != "") {
      $day =  $this->getWeddingdate()->format('d');
      return $day;
    }
    return "";
  }

  function getWeddingMonth()
  {
    if (!is_null($this->getWeddingdate()) && $this->getWeddingdate() != "") {
      $month = $this->getWeddingdate()->format('m');
      return $month;
    }
    return "";
  }

  public function postInsert(ConnectionInterface $con = null)
  {
    $this->createTimeLineNote(true);
  }

  public function postUpdate(ConnectionInterface $con = null)
  {
    $this->createTimeLineNote(false);
  }

  private function createTimeLineNote($new)
  {
    $note = new Note();
    $note->setFamId($this->getId());

    if ($new) {
      $note->setText("Created");
      $note->setType("create");
      $note->setEnteredBy($this->getEnteredBy());
      $note->setDateLastEdited($this->getDateEntered());
    } else {
      $note->setText("Updated");
      $note->setType("edit");
      $note->setEnteredBy($this->getEditedBy());
      $note->setDateLastEdited($this->getDateLastEdited());
    }

    $note->save();
  }
  
  function getUploadedPhoto()
  {
    $validextensions = array("jpeg", "jpg", "png");
    $hasFile = false;
    while (list(, $ext) = each($validextensions)) {
      $photoFile = SystemURLs::getImagesRoot() ."/Family/thumbnails/" . $this->getId() . "." . $ext;
      if (file_exists($photoFile)) {
        $hasFile = true;
        $photoFile = SystemURLs::getImagesRoot() ."/Family/thumbnails/" . $this->getId() . "." . $ext;
        break;
      }
    }

    if ($hasFile) {
      return $photoFile;
    } else {
      return "";
    }
  }
  
  function getPhoto()
  {
    $familyPhoto = new \stdClass();
    $familyPhoto->type = "localFile";
    $familyPhoto->path = $this->getUploadedPhoto();
    if ($familyPhoto->path == "") {
      $familyPhoto->type = "remoteFile";
      $familyPhoto->path =   $photoFile = SystemURLs::getRootPath() . "/Images/Family/family-128.png";
    }
    
    return $familyPhoto;
  }
  
  public function deletePhoto()
  {
    if ($_SESSION['bAddRecords'] || $bOkToEdit ) {
      $note = new Note();
      $note->setText("Profile Image Deleted");
      $note->setType("photo");
      $note->setEntered($_SESSION['iUserID']);
      PhotoUtils::deletePhotos("Family", $this->getId());
      $note->setPerId($this->getId());
      $note->save();
      return true;
    }
    return false;
  }

}
