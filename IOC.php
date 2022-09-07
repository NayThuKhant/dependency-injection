<?php


class IOC
{
    //  Get an object instance of a class
    public static function getInstance($className)
    {
        $paramArr = self::getMethodParams($className);
        return (new ReflectionClass($className))->newInstanceArgs($paramArr);
    }

    public static function make($className, $methodName, $params = [])
    {
        //  Gets an instance of a class
        $instance = self::getInstance($className);
        //  Get the parameters of dependency injection required by this method
        $paramArr = self::getMethodParams($className, $methodName);
        return $instance->{$methodName}(...array_merge($paramArr, $params));
    }


    protected static function getMethodParams($className, $methodsName = '__construct')
    {
        try {
            $class = new ReflectionClass($className);
        } catch (Exception $exception) {
            throw new Exception("Class can\'t be instantiated, reason -> {$exception->getMessage()}");
        }

        $paramsArray = [];
        if ($class->hasMethod($methodsName)) {
            $construct = $class->getMethod($methodsName);
            $params = $construct->getParameters();
            if (count($params) > 0) {

                foreach ($params as $key => $param) {
                    if ($paramClass = $param->name) {
                        $paramClassName = ucfirst($paramClass);

                        $args = self::getMethodParams($paramClassName);
                        $paramsArray[] = (new ReflectionClass($paramClassName))->newInstanceArgs($args);
                    }
                }
            }
        }
        return $paramsArray;
    }
}
