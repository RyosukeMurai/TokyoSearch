<?php
/**
 * Created by PhpStorm.
 * User: ryosuke
 * Date: 15/08/27
 * Time: 1:28
 */

namespace app\commands;


use app\components\FileHelper;
use app\models\Instagram;
use Faker\Provider\File;
use yii\console\Controller;
use yii\helpers\Url;

class CrawlerController extends Controller
{

    const INSTAGRAM_NEXT_URL = '@app/commands/.tmp/ins_next';
    const INSTAGRAM_TAGS = '@app/commands/.tmp/ins_tags';

    /**
     * @param $param
     * @return int
     */
    public function actionInstagram($param, $limit = null)
    {
        //resume before
        $resume = $this->resume();

        $current = 0;
        if(!empty($resume)) {
            if($this->crawl($resume, $limit, $current)){
                return Controller::EXIT_CODE_NORMAL;
            }
        }
        $tags = $this->getTags($param);

        while (!empty($tags)) {

            $name = array_shift($tags);
            FileHelper::writeText(Url::to(static::INSTAGRAM_TAGS), $tags, true);

            echo $name."\n";
            $data = Instagram::getPostsByTag($name);
            if(!isset($data))continue;

            Instagram::savePosts($data);

            echo $current."\n";
            if (isset($data->pagination) && isset($data->pagination->next_url)) {
                if($this->crawl($data->pagination->next_url, $limit, $current)){
                    return Controller::EXIT_CODE_NORMAL;
                }
            } else {
                if(isset($limit) && $limit < ++$current){
                    echo "limit is over tag\n";
                    return 1;
                }
            }
        }
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * @param int $limit
     * @return $resume string
     */
    private function resume()
    {
        $resume = FileHelper::getText(Url::to(static::INSTAGRAM_NEXT_URL));
        if(!empty($resume)) {
            FileHelper::removeFile(Url::to(static::INSTAGRAM_NEXT_URL));
        }
        return $resume;
    }

    /**
     * @param $condition
     */
    private function getTags($condition)
    {
        $tags = FileHelper::getTextToArray(Url::to(static::INSTAGRAM_TAGS));

        if(empty($tags)){
            $tags = Instagram::getTagsNameArray($condition);
            FileHelper::writeText(Url::to(static::INSTAGRAM_TAGS), $tags, true);
        }

        return $tags;
    }

    /**
     * @param $url
     * @param null $limit
     * @param int $current
     */
    private function crawl($url, $limit = null, &$current = 0)
    {
        echo "call crawl\n";
        echo $current."\n";
        echo $limit."\n";

        if(isset($limit) && $limit < $current){
            echo "limit is over\n";
            echo "save next:$url\n";
            FileHelper::writeText(Url::to(static::INSTAGRAM_NEXT_URL), $url, true);
            return 1;
        }

        $data = Instagram::httpGet($url);
        Instagram::savePosts($data);

        if(!isset($data->pagination) || !isset($data->pagination->next_url)){
            echo "end page\n";
            return 0;
        }

        return $this->crawl($data->pagination->next_url, $limit, ++$current);
    }
}