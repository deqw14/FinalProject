<?php

use Dedoc\Scramble\Http\Middleware\RestrictedDocsAccess;

return [
    /*
     * The API route prefix. All routes starting with this prefix will be included
     * in the generated OpenAPI documentation.
     */
    'api_path' => 'api',

    /*
     * The API domain. If set, only routes on this domain will be included.
     */
    'api_domain' => null,

    /*
     * General info about your API. This will populate the OpenAPI `info` object.
     */
    'info' => [
        'version' => env('APP_VERSION', '1.0.0'),
        'description' => <<<'MD'
        ## Gym Management API

        REST API for a gym management Flutter & Web application.

        ### Authentication
        This API uses **Bearer token** authentication via Laravel Sanctum.
        Include the token in the `Authorization` header:
        ```
        Authorization: Bearer {your_token}
        ```

        ### Occupancy
        Gym occupancy is returned as a **percentage** of max capacity.
        Poll `GET /api/gyms/{id}/capacity` every 30 seconds for near real-time updates.

        ### Payment (Demo)
        Use card number `4111111111111111` for successful subscription purchases.
        Any other card number will be declined.

        ### Trainer Matching
        Trainers are matched by comparing the user's onboarding **goal** with trainer **skill tags**:
        `weight_loss`, `muscle_gain`, `crossfit`, `yoga`, `pilates`, `rehabilitation`.
        MD,
    ],

    /*
     * Paths where the generated documentation will be served.
     */
    'docs_path'  => 'docs/api',
    'docs_json_path' => 'docs/api.json',
    'docs_yaml_path' => 'docs/api.yaml',

    /*
     * Middleware applied to the documentation routes.
     * Remove RestrictedDocsAccess to make docs fully public.
     */
    'middleware' => [
        'web',
        // RestrictedDocsAccess::class,
    ],

    'exclude_routes' => [],

    'extensions' => [],
];
