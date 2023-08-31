<?php

namespace App\Repository\QueryBuilders;

interface IQueryBuilder
{
    /**
     * This method allows adding filters when creating a request. If you cumulate calls to this method, the conditions
     * given will be verified via the AND operator (so if you need to make a OR check, you should build it on your own
     * before to call this method).
     * <br>
     * Also, if you pass variable values, make sure to include them in the dictionary that will be passed to the
     * `getMatches()` method.
     *
     * @param string $condition The condition to add to the request.
     * @return IQueryBuilder The current instance (so that you can cumulate calls straightaway).
     */
    public function where(string $condition): IQueryBuilder;

    /**
     * Adds conditions on the given keywords for every filterable attribute of the concerned entity.
     * <br>
     * Those conditions will be encompassed by parenthesis, and the operator between each will be `OR`. The way these
     * should be verified is via a `LIKE` comparison, with the token being surrounded by percent symbols.
     *
     * @param array $tokens A list of token to look for.
     * @return IQueryBuilder The current instance (so that you can cumulate calls straightaway).
     */
    public function addKeyWords(array $tokens): IQueryBuilder;

    /**
     * This method will return an array containing all the instances of the type of entity looked for that matches all
     * the given conditions.
     * <br>
     * These instances should be built correctly, just like what you would get when calling the `getAll()` method from
     * the corresponding repository service.
     * <br>
     * **Note:** While it is a bad practice to send more variables than necessary, doing so will not slow down the
     * request to the database by any mean. Those values will simply not be read. The most dangerous consequence you may
     * face by doing so, is to create memory leaks, as more memory than required will be used.
     *
     * @param array $variables An array that should contain the values for all variables contained within the conditions
     * @return array The built entities matching the conditions.
     */
    public function getMatches(array $variables): array;
}