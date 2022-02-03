<?php

 Namespace storage;

 use support\proxies\Config;

use exceptions\RuntimeException;


 class FileMetadata
 {

   public function __construct($file = [])
   {
       $this->setMetadata($file);
   }

   /**
    * set file properties
    *
    * @return string
   */
   public function __set($metaData, $value)
   {
       $this->$metaData = $value;

       $name = isset($this->name) ? $this->name : '';

       $this->setUniqueName($name);

       return $this;
   }


   /**
    * set file properties
    *
    * @return string
   */
   public function setMetadata($metaData = [])
   {
       if (!empty($metaData))
       {
           foreach ($metaData as $data => $value)
           {
               $this->$data = $value;
           }
       }

       return $this;
   }


    /**
     * set file properties
     *
     * @return string
    */
    public function setUniqueName($name)
    {
       $this->uniqueName = uniqid($name, true) . $name;

       return $this;
    }

   /**
    * get file properties
    *
    * @return string
   */
   public function __get($metaData)
   {
      if (property_exists($this, $metaData))
      {
          return $this->$metaData;
      }

      throw new RuntimeException("Undifined property {$metaData} in File::class");
   }


 }
