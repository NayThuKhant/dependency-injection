<?php


class IOC
{
    /**
     * @throws ReflectionException
     */
    public static function getInstance($className): object
    {
        $paramArray = self::getMethodParams($className);
        return (new ReflectionClass($className))->newInstanceArgs($paramArray);
    }

    /**
     * @throws Exception
     */
    public static function make($className, $methodName, $params = [])
    {
        $instance = self::getInstance($className);

        $paramArray = self::getMethodParams($className, $methodName);
        return $instance->{$methodName}(...array_merge($paramArray, $params));
    }


    /**
     * @throws ReflectionException
     * @throws Exception
     */
    protected static function getMethodParams($className, $methodsName = '__construct'): array
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
