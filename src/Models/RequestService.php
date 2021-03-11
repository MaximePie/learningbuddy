<?php

use Symfony\Component\HttpFoundation\Request;

/**
 * This file contains methods for request treatment
 */


abstract class RequestService {

    /**
     * Returns the value of the parameter from the request
     * @param Request $request
     * @param string $parameter
     * @return string
     */
    public static function getFromRequest(Request $request, string $parameter): string
    {
        $content = $request->getContent();
        $jsonParameters = json_decode($content, true);

        if (!$jsonParameters) {
            throw new RuntimeException("La requête ne dispose pas de contenu.");
        }

        if (!$jsonParameters[$parameter]) {
            throw new RuntimeException("Le paramètre $parameter n'a pas été trouvé dans la requête.");
        }

        return $jsonParameters[$parameter];
    }
}
