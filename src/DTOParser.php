<?php
namespace DTOParser;

class DTOParser
{

    static function toDTO($data, $class) {
        $reflection = new \ReflectionMethod($class.'::__construct');
        return self::getInstance($reflection, $data, $class);
    }

    static function toDTOArray($data, $class) {
        $reflection = new \ReflectionMethod($class.'::__construct');
        $result = [];
        foreach($data as $row) {
            $result[] = self::getInstance($reflection, $row, $class);
        }
        return $result;
    }

    protected static function getInstance($reflection, $data, $class) {
        if(!is_array($data)) {
            $data = json_decode(json_encode($data), true);
        }
        $arguments = self::resolve($reflection, $data);
        return new $class(...$arguments);
    }


    protected static function resolve(\ReflectionFunctionAbstract $reflection, array $parameters) : array
    {
        if (!$number = $reflection->getNumberOfParameters()) {
            return [];
        }

        $arguments = \array_fill(0, $number, null);
        foreach ($reflection->getParameters() as $pos => $parameter) {
            $result = self::match($parameter, $parameters);
            if ($result) {
                $parameterClass = $parameter->getClass();
                $variableClass = gettype($result[1]) === 'object' ? get_class($result[1]) : null;
                if(!empty($parameterClass) && $parameterClass !== $variableClass && is_array($result[1])) {
                    $variable = self::toDTO($result[1], $parameterClass->name);
                } else {
                    $variable = $result[1];
                }
                $arguments[$pos] = $variable;
                unset($parameters[$result[0]]);
                continue;
            }
            if ($parameter->isDefaultValueAvailable()) {
                $arguments[$pos] = $parameter->getDefaultValue();
                continue;
            }
        }
        return $arguments;
    }

    protected static function match(\ReflectionParameter $parameter, array $parameters) : ?array
    {
        return \array_key_exists($parameter->name, $parameters)
            ? [$parameter->name, $parameters[$parameter->name]]
            : null;
    }
}
