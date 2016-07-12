<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'role', 'vendorId', 'isPasswordReset'], 'integer'],
            [['email', 'password', 'firstName','lastName', 'streetAddress','businessName', 'phoneNumber', 'billingName', 'billingStreetAddress', 'date_created', 'date_updated', 'stripeId', 'cardLast4', 'cardExpiry', 'city', 'state', 'billingCity', 'billingState', 'billingPhoneNumber'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'role' => $this->role,
            'vendorId' => $this->vendorId,
            'date_created' => $this->date_created,
            'date_updated' => $this->date_updated,
            'isPasswordReset' => $this->isPasswordReset,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'businessName', $this->businessName])
            ->andFilterWhere(['like', 'firstName', $this->firstName])
            ->andFilterWhere(['like', 'lastName', $this->lastName])
            ->andFilterWhere(['like', 'streetAddress', $this->streetAddress])
            ->andFilterWhere(['like', 'phoneNumber', $this->phoneNumber])
            ->andFilterWhere(['like', 'billingName', $this->billingName])
            ->andFilterWhere(['like', 'billingStreetAddress', $this->billingStreetAddress])
            ->andFilterWhere(['like', 'stripeId', $this->stripeId])
            ->andFilterWhere(['like', 'cardLast4', $this->cardLast4])
            ->andFilterWhere(['like', 'cardExpiry', $this->cardExpiry])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'billingCity', $this->billingCity])
            ->andFilterWhere(['like', 'billingState', $this->billingState])
            ->andFilterWhere(['like', 'billingPhoneNumber', $this->billingPhoneNumber]);

        return $dataProvider;
    }
}
