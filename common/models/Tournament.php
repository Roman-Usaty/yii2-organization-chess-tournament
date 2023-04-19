<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tournament".
 *
 * @property int $id
 * @property string $name
 * @property int $author
 * @property string|null $description
 * @property int|null $minRank
 * @property int|null $maxRank
 * @property int|null $isActive
 *
 * @property User $author0
 */
class Tournament extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tournament';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'author'], 'required'],
            [['author', 'minRank', 'maxRank', 'isActive'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'author' => 'Author',
            'description' => 'Description',
            'minRank' => 'Min Rank',
            'maxRank' => 'Max Rank',
            'isActive' => 'Is Active',
        ];
    }

    /**
     * Gets query for [[Author0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor0()
    {
        return $this->hasOne(User::class, ['id' => 'author']);
    }

    /**
     * Finds tournament by id
     *
     * @param integer $id
     * @return static|null
     */
    public function findById($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * Finds tournament by name
     *
     * @param string $name
     * @return static|null
     */
    public function FindByName($name)
    {
        return static::findOne(['name' => $name]);
    }
}
