<?php 
//Invisibly replace any occurance of the articleimages folder with the correct path
@symlink('socket/modules/media/images', 'articleimages');
?>