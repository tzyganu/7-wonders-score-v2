<?php
namespace App;

class EntityMapper
{
    /**
     * @return array
     */
    public function getEntityMap()
    {
        return [
            'player' => [
                'class' => \App\Entity\Player::class,
                'messages' => [
                    'save' => 'Player was saved',
                    'not-exists' => 'Player with id {id} does not exist'
                ]
            ],
            'achievement' => [
                'class' => \App\Entity\Achievement::class,
                'messages' => [
                    'save' => 'Achievement was saved',
                    'not-exists' => 'Achievement with id {id} does not exist'
                ]
            ],
            'achievement-group' => [
                'class' => \App\Entity\AchievementGroup::class,
                'messages' => [
                    'save' => 'Achievement group was saved',
                    'not-exists' => 'Achievement group with id {id} does not exist'
                ]
            ],
            'achievement-color' => [
                'class' => \App\Entity\AchievementColor::class,
                'messages' => [
                    'save' => 'Achievement Color was saved',
                    'not-exists' => 'Achievement Color with id {id} does not exist'
                ]
            ],
            'category' => [
                'class' => \App\Entity\Category::class,
                'messages' => [
                    'save' => 'Score Category was saved',
                    'not-exists' => 'Score Category with id {id} does not exist'
                ]
            ],
            'wonder' => [
                'class' => \App\Entity\Wonder::class,
                'messages' => [
                    'save' => 'Wonder was saved',
                    'not-exists' => 'Wonder with id {id} does not exist'
                ]
            ],
            'wonder-set' => [
                'class' => \App\Entity\WonderSet::class,
                'messages' => [
                    'save' => 'Wonder set was saved',
                    'not-exists' => 'Wonder set with id {id} does not exist'
                ]
            ],
        ];
    }

    /**
     * @param $entity
     * @return mixed
     * @throws \Exception
     */
    public function getEntityName($entity)
    {
        $map = $this->getEntityMap();
        if (isset($map[$entity]['class'])) {
            return $map[$entity]['class'];
        }
        throw new \Exception("Cannot find repository for entity ".$entity);
    }

    /**
     * @param $entity
     * @return mixed
     * @throws \Exception
     */
    public function getSaveMessage($entity)
    {
        $map = $this->getEntityMap();
        if (isset($map[$entity]['messages']['save'])) {
            return $map[$entity]['messages']['save'];
        }
        throw new \Exception("Cannot find repository for entity ".$entity);
    }

    /**
     * @param $entity
     * @return mixed
     * @throws \Exception
     */
    public function getNotExistsMessage($entity, $id)
    {
        $map = $this->getEntityMap();
        if (isset($map[$entity]['messages']['not-exists'])) {
            return str_replace('{id}', $id, $map[$entity]['messages']['not-exists']);
        }
        throw new \Exception("Cannot find repository for entity ".$entity);
    }
}
