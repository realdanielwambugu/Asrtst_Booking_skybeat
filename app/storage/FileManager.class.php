<?php

 Namespace storage;

 use support\proxies\Config;


 class FileManager
 {

   protected $file;

   protected $metaData;


   public function check(array $file)
   {
        if (is_multi_array($file))
        {
            return $this->checkMultipleFiles($file);
        }

        return $this->resolveMetadata($file);
   }


   protected function resolveMetadata($file = [])
   {
       return new FileMetadata($file);
   }


   protected function checkMultipleFiles(array $metaDatas)
   {
       $files = [];

       foreach ($metaDatas as $metaData => $values)
       {
           foreach ($values as $key => $value)
           {
               if (isset($files[$key]))
               {
                   $files[$key]->$metaData = $value;
               }
               else
               {
                   $files[$key] = $this->resolveMetadata();

                   $files[$key]->$metaData = $value;
               }
           }
       }

      return $files;
   }

   public function hasFile($file, $pathName, $root = 'public')
   {
       return file_exists($this->getUrl($file, $pathName, $root));
   }

   public function pushFile($file, $pathName, $root = 'public')
   {
       $file = $this->check($file);

       $directory = '../' . $root . '/' . $this->getDirectoryPath($root, $pathName);

       if (is_array($file))
       {
           return $this->uploadMultipleFiles($file, $directory);
       }

      $this->uploadFile($file, $directory);

      return $file;
   }


   protected function getDirectoryPath($root, $pathName)
   {
       return Config::get("default.paths.storagePaths.{$root}.{$pathName}");
   }


   protected function uploadMultipleFiles(array $files, $diectory)
   {
       foreach ($files as $file)
       {
           $this->uploadFile($file, $diectory);
       }

       return $files;
   }


   protected function uploadFile($file, $diectory)
   {
       return move_uploaded_file($file->tmp_name, $diectory . $file->uniqueName);
   }

   public function pullFile($file, $pathName, $root = 'public')
   {
       $url = $this->getUrl($file, $pathName, $root);

       return $this->check(pathinfo($url));
   }

   public function renameFile($from, $to, $pathName, $root = 'public')
   {
      $path = $this->getUrl('', $pathName, $root);

      rename($path . $from, $path . $to);

      return $this;
   }

   public function copyFile($from, $to, $pathName, $root = 'public')
   {
       $path = $this->getUrl('', $pathName, $root);

       copy($path . $from, '../' . $root . '/' . $to);

       return $this;
   }

   public function moveFile($from, $to, $pathName, $root = 'public')
   {
      $path = $this->getUrl('', $pathName, $root);

      rename($path . $from, '../' . $root . '/' . $to);

      return $this;
   }

   public function getUrl($file, $pathName, $root)
   {
     return '../' . $root . '/' . $this->getDirectoryPath($root, $pathName) . $file;
   }

   public function delete($file, $pathName, $root = 'public')
   {
       return unlink($this->getUrl($file, $pathName, $root));
   }

}
