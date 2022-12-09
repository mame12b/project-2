<?php

namespace App\Models;

Trait GenerateAvatar
{
    /**
     * generate avatar
     *
     * @param string $name
     * @return string
     */
    public function generateAvatar(string $name): string
    {
        // formatting name
        $text = '';

        if(preg_match_all('/\b\w/', $name, $matches)){
            foreach($matches[0] as $match){
             $text .= $match.' ';
            }
        }

        $text = ucwords($text);

        // creating image
        $image = imagecreatetruecolor(60, 60);

        // Create some colors
        $white = imagecolorallocate($image, rand(10,200), rand(10,200), rand(10,200));
        $black = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, 0, 0, 59, 59, $white);

        //specifying font
        $font = public_path().'/FFF_Tusj.ttf';

        // center text
        $bbox = imagettfbbox(20, 0, $font, $text);
        $center1 = (imagesx($image) / 2) - (($bbox[2] - $bbox[0]) / 2);

        // Add the text
        imagettftext($image, 20, 0, $center1, 40, $black, $font, $text);

        $image_name = 'avatar_' . time() . '_' . str_replace(' ', '_', $name) . '.jpg';

        // making sure avatar dir exists
        if(!is_dir(public_path("/uploads/avatar/"))){
            mkdir(public_path("/uploads/avatar/"));
        }

        imagejpeg($image, public_path("/uploads/avatar/$image_name"), 100);

        $this->update(['avatar' => $image_name]);

        return  $image_name;
    }
}
