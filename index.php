<?php

/**
 * Teamlead T-70
 */
class Teamlead
{
    /**
     * Options
     * @var array
     */
    private $options;

    /**
     * State
     * @var integer
     */
    private $state;

    /**
     * __construct of instance
     * @param array   $options
     * @param integer $stateDefault
     */
    function __construct($options, $stateDefault)
    {
        $this->options = $options;
        $this->state = $stateDefault;
    }

    public function reviewCode(Junior $junior)
    {
        // reviewing code
        $isGoodCode = $junior->getCode();

        // set new state
        $isGoodCode
            ? $this->stateUp($junior)
            : $this->stateDown($junior);
    }

    /**
     * Change state to up
     * @param  Junior $junior
     * @return void
     */
    private function stateUp(Junior $junior)
    {
        $prediction_state = $this->options['states'][$this->state];

        if ($prediction_state['countable'] and $prediction_state['up'] == $this->state) {
            $junior->addGoodJob();
        }

        $this->state = $prediction_state['up'];

        $this->saySomething($this->options['vocabulary']['good']);
    }

    /**
     * Change state to down
     * @param  Junior $junior
     * @return void
     */
    private function stateDown(Junior $junior)
    {
        $prediction_state = $this->options['states'][$this->state];

        if ($prediction_state['countable'] and $prediction_state['down'] == $this->state) {
            $junior->addBadJob();
        }

        $this->state = $prediction_state['down'];

        $this->saySomething($this->options['vocabulary']['bad']);
    }

    /**
     * say
     * @param  array $vocabulary
     * @return void
     */
    private function saySomething($vocabulary)
    {
        echo "{$vocabulary[array_rand($vocabulary)]} \n";
    }

    /**
     * train Junior
     * @param  Junior $junior
     * @return void
     */
    public function trainJunior(Junior $junior)
    {
        // get junior's Learning Ability
        $la = $junior->getLearningAbility();

        $time_for_training = rand(1, 50);
        for ($i=0; $i < $time_for_training; $i++) { 
            $shift = rand(20, 90) / 100;
            $junior->train($shift * $la + $la);
        }
    }
}


/**
 * Junior T-69
 */
class Junior
{
    /**
     * bad job
     * @var integer
     */
    private $badJob = 0;

    /**
     * good job
     * @var integer
     */
    private $goodJob = 0;

    /**
     * learning ability
     * @var float
     */
    private $learningAbility;

    /**
     * experience
     * @var float
     */
    private $experience = 0.0;

    public function __construct(float $learningAbility)
    {
        $this->learningAbility = $learningAbility;
    }

    /**
     * get Learning Ability
     * @return float
     */
    public function getLearningAbility()
    {
        return $this->learningAbility;
    }

    /**
     * get code
     * @return integer
     */
    public function getCode()
    {
        // TODO: some formula
        // $this->experience * $this->learningAbility
        return rand(0, 1);
    }

    /**
     * get Bad Job
     * @return integer
     */
    public function getBadJob()
    {
        return $this->badJob;
    }

    /**
     * get Good Job
     * @return integer
     */
    public function getGoodJob()
    {
        return $this->goodJob;
    }

    /**
     * add Good Job
     * @return void
     */
    public function addGoodJob()
    {
        $this->goodJob++;
    }


    /**
     * add Good Job
     * @return void
     */
    public function addBadJob()
    {
        $this->badJob++;
    }


    /**
     * training
     * @param  float $efficiency
     * @return void
     */
    public function train(float $efficiency)
    {
        $this->experience += $efficiency;
        $this->learningAbility += $efficiency - rand(6, 12) / 100;
    }
}


/**
 * HR T-1000
 */
class HumanResource
{
    public function checkJunior(Junior $junior)
    {
        $times = $junior->getBadJob();
        echo "bad jobs: {$times}\n";
    }
}


/**
 * Manager T-1001
 */
class Manager
{
    public function checkJunior(Junior $junior)
    {
        $times = $junior->getGoodJob();
        echo "good jobs: {$times}\n";
    }
}






//===========================================


$teamlead = new Teamlead([
    'states' => [
        1 => [
            'name' => 'Хорошие настроение',
            'up' => 1,
            'down' => 2,
            'countable' => true,
        ],
        2 => [
            'name' => 'Нормальное настроение',
            'up' => 1,
            'down' => 3,
            'countable' => false,
        ],
        3 => [
            'name' => 'Плохое настроение',
            'up' => 2,
            'down' => 4,
            'countable' => false,
        ],
        4 => [
            'name' => 'Не попадись на глаза',
            'up' => 3,
            'down' => 4,
            'countable' => true,
        ],
    ],
    'vocabulary' => [
        'good' => [
            '+ Хороший код',
            '+ Молодец',
            '+ Позьми пирожок',
        ],
        'bad' => [
            '- Надо ещё учится',
            '- Перепиши это',
            '- ПР не сделаю'
        ]
    ]
], 1);



// hired new juniors
$junior_1 = new Junior( rand(1, 100) / 100 );
// $junior_2 = new Junior( rand(1, 100) / 100 );

// train juniors
$teamlead->trainJunior($junior_1);
// $teamlead->trainJunior($junior_2);


// juniors start work
$commits = rand(2, 50);
for ($i=0; $i < $commits; $i++) { 
    $teamlead->reviewCode($junior_1);
    // $teamlead->reviewCode($junior_2);
}


// train juniors again
$teamlead->trainJunior($junior_1);
// $teamlead->trainJunior($junior_2);



// juniors start work again
$commits = rand(2, 50);
for ($i=0; $i < $commits; $i++) { 
    $teamlead->reviewCode($junior_1);
    // $teamlead->reviewCode($junior_2);
}


// hired HR and manager
$hr = new HumanResource;
$manager = new Manager;


// check junior 1
$hr->checkJunior($junior_1);
$manager->checkJunior($junior_1);

// check junior 2
// $hr->checkJunior($junior_2);
// $manager->checkJunior($junior_2);


function dd() {
    print_r(func_get_args()) . "\n";
    die;
}

dd($junior_1/*, $junior_2*/);