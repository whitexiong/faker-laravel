<?php

namespace Illuminate\Container;

use ReflectionClass;
use ReflectionException;

class Container
{
    /**
     * 解析依赖
     * @param $abstract
     * @return object
     */
    public function make($abstract): object
    {
        try {
            return $this->resolve($abstract);
        } catch (ReflectionException $e) {
        }
    }

    /**
     * 解析绑定
     * @param $abstract
     * @return object
     * @throws ReflectionException
     */
    protected function resolve($abstract): object
    {
        $concrete = $abstract;
        return  $this->build($concrete);
    }

    /**
     * 构建实例
     * @param $concrete
     * @return object
     * @throws ReflectionException
     */
    protected function build($concrete): object
    {
        $reflector =  new ReflectionClass($concrete);

        $constructor =  $reflector->getConstructor(); //获取构造函数

        if(is_null($constructor)){
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();

        $instance = $this->resolveDependencies($dependencies);

        return $reflector->newInstanceArgs($instance);
    }

    /**
     * 解析依赖
     * @param  array  $dependencies
     * @return array
     */
    protected function resolveDependencies(array $dependencies): array
    {
        $results = [];
        foreach ($dependencies as $dependency){
            $results[] = $this->make($dependency->getName());
        }

        return $results;
    }


}