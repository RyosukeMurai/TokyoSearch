<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Instagram]].
 *
 * @see Instagram
 */
class InstagramQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Instagram[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Instagram|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}