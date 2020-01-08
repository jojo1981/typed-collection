UPGRADE FROM 1.x to 2.0
=======================

typed collection data structure
-----------------

The `getType` method of the `\Jojo1981\TypedCollection\Collection` will still return a string value, but some types are not represented exactly as before.

| Typed collection 1.x                                  | Typed collection 2.x
| ----------------------------------------------------- | -----------------------------------------------------
| boolean                                               | bool
| integer                                               | int
| stdClass                                              | \stdClass
| DateTime                                              | \DateTime
| Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity | \Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity

Class names are now prefixed with the namespace separator. 