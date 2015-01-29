<?php

use Hoa\Ruler\Ruler;

use Exception\TargetUnsupportedException;
use Executor\Executor;

class RulerZ
{
    private $executors = [];

    public function __construct(array $executors = [])
    {
        foreach ($executors as $executor) {
            $this->registerExecutor($executor);
        }
    }

    public function registerExecutor(Executor $executor)
    {
        $this->executors[] = $executor;
    }

    public function filter($target, $rule, array $parameters = array())
    {
        $executor = $this->findExecutor($target);
        $ast = $this->parse($rule);

        return $executor->filter($target, $ast, $parameters);
    }

    private function findExecutor($target)
    {
        foreach ($this->executors as $executor) {
            if ($executor->supports($target)) {
                return $executor;
            }
        }

        throw new TargetUnsupportedException('The given target is not supported.');
    }

    private function parse($rule)
    {
        return Ruler::interprete($rule);
    }
}
