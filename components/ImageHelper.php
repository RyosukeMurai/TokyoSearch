<?php
/**
 * Created by PhpStorm.
 * User: ryosuke
 * Date: 15/08/30
 * Time: 18:46
 */

namespace app\components;


use yii\helpers\Url;

class ImageHelper
{

    const PING_BASE_RED = "@app/components/.resource/base/red.png";
    const PING_BASE_BLUE = "@app/components/.resource/base/blue.png";
    const PING_BASE_GREEN = "@app/components/.resource/base/green.png";
    const PING_MASK = "@app/components/.resource/base/mask.png";
    const PING_IMG_DIR = "@app/web/.resource/";

    public static function createPingImg($imgurl, $ping = ImageHelper::PING_BASE_RED)
    {

        $ping = Url::to($ping);

        //ファイルから新規にJPEG画像を作成する
        $image_back = imagecreatefrompng($ping);
        $image_alpha = static::createImageByMask($imgurl);

        //背景画像の幅と高さを取得
        list($width, $height, $type, $attr) = getimagesize($ping);

        //合成する画像をちょいとずらしてみる
        $x = 32;
        $y = 32;

        //imagealphablending($image_back, false);
        //imagealphablending($image_alpha, false);
        imagesavealpha($image_back, true);
        imagesavealpha($image_alpha, true);

        //画像を合成する
        imagecopy(
            $image_back,  //コピー先の画像リンクリソース
            $image_alpha, //コピー元の画像リンクリソース
            $x,           //コピー先の x 座標
            $y,           //コピー先の y 座標
            0,            //コピー元の x 座標
            0,            //コピー元の y 座標
            imagesx($image_alpha),       //コピー元の幅
            imagesy($image_alpha)      //コピー元の高さ
        );
        //合成した画像を出力
        //header("Content-type: image/png");
        //imagepng($image_back);

        $filename = basename($imgurl, ".jpg").".png";
        $filepath = Url::to(static::PING_IMG_DIR).$filename;
        imagepng($image_back, $filepath);

        //メモリから解放
        imagedestroy($image_back);
        imagedestroy($image_alpha);

    }

    public static function createImageByMask($src, $maskpath = ImageHelper::PING_MASK)
    {
        $canvas  = new \stdClass();
        $image   = new \stdClass();
        $mask    = new \stdClass();

        $image->image     = imagecreatefromjpeg($src);
        $mask->image  = imagecreatefrompng(Url::to($maskpath));

        $image->width     = imagesx($image->image);
        $image->height    = imagesy($image->image);

        $mask->width  = imagesx($mask->image);
        $mask->height     = imagesy($mask->image);

        // Resize image if necessary
        if( $image->width != $mask->width || $image->height != $mask->height) {
            $tempPic = imagecreatetruecolor( $mask->width, $mask->height );
            imagecopyresampled( $tempPic, $image->image, 0, 0, 0, 0, $mask->width, $mask->height, imagesx( $image->image ), imagesy( $image->image ) );
            imagedestroy( $image->image );
            $image->image = $tempPic;
            $image->width = imagesx($image->image);
            $image->height = imagesy($image->image);
        }

        $canvas->width    = $mask->width;
        $canvas->height   = $mask->height;
        $canvas->image = imagecreatetruecolor($canvas->width, $canvas->height);

        imagealphablending($canvas->image, false);
        imagesavealpha($canvas->image, true);
        $transparent = imagecolorallocatealpha( $canvas->image, 0, 0, 0, 127 );
        imagefill( $canvas->image, 0, 0, $transparent );

        //中心から切り抜くための調整
        $top     = round(($image->width - $mask->width) / 2);
        $left    = round(($image->height - $mask->height) / 2);

        for($y=0;$y<$canvas->height;$y++){
            for($x=0;$x<$canvas->width;$x++){
                $rgb     = imagecolorat($mask->image, $x, $y);
                $index   = imagecolorsforindex($mask->image, $rgb);

                $alpha   = $index['alpha'];
                //$alpha     = ($index['red'] + $index['green'] + $index['blue']) / 765 * 127 ;

                $current = imagecolorat($image->image, $x + $top, $y + $left);
                $index   = imagecolorsforindex($image->image, $current);
                $color   = imagecolorallocatealpha($canvas->image, $index['red'], $index['green'], $index['blue'], $alpha);
                imagesetpixel($canvas->image, $x, $y, $color);
            }
        }
        //imagedestroy($canvas->image);
        imagedestroy($image->image);
        imagedestroy($mask->image);
        return $canvas->image;
    }
}