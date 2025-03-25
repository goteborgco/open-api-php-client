# Göteborg & Co PHP Client

Official PHP API Client for interacting with the Göteborg & Co GraphQL API. This composer package provides a convenient way to access Göteborg & Co's data platform, offering both type-safe methods for common operations and flexible raw GraphQL query capabilities.

## Features

- Type-safe methods for common operations (guides, events, places)
- Direct GraphQL query support for advanced use cases
- Built-in error handling and response parsing
- Comprehensive documentation and examples
- Modern PHP 7.4+ with type hints

## Installation

Install the package via composer:

```bash
composer require gbgco/php-api-client
```

## Setup

Initialize the API client with your API URL and subscription key:

```php
use GBGCO\API;

$apiUrl = 'https://apim-openapi-gbgco-prod.azure-api.net/gql';
$subscriptionKey = 'your-subscription-key';

$api = new API($apiUrl, $subscriptionKey);
```

## Usage

### General GraphQL Queries

The API provides a general-purpose query method for executing any GraphQL query. This method accepts raw GraphQL syntax and returns the unprocessed API response:

```php
$query = <<<GQL
    query {
        guides(filter: { lang: "sv", per_page: 10, page: 1 }) {
            guides {
                date
                dates {
                    end
                    start
                }
                excerpt
                title
            }
        }
    }
GQL;

$result = $api->query($query);
```

#### Query Structure

The query method expects a complete GraphQL query string. The query must:
- Start with the `query` keyword
- Include any necessary filters in the GraphQL syntax
- Specify all required fields and nested structures

Examples of valid queries:

```php
// Simple query with basic fields
$result = $api->query('
    query {
        guides {
            guides {
                id
                title
            }
        }
    }
');
```

#### Error Handling

The query method will throw exceptions for:
- Empty queries
- Invalid GraphQL syntax
- API errors

Always wrap query calls in try-catch blocks:

```php
try {
    $result = $api->query($query);
} catch (\InvalidArgumentException $e) {
    // Handle empty or invalid queries
    echo "Query Error: " . $e->getMessage();
} catch (\Exception $e) {
    // Handle API errors
    echo "API Error: " . $e->getMessage();
}
```

### Type-Specific Methods

While the general query method provides full flexibility, the API also includes type-specific methods for common operations. These methods provide:
- Type safety
- Simpler parameter handling
- Structured field selection
- Default values for common use cases

The following sections detail these type-specific methods.

### Guides

The API provides methods to list guides and retrieve individual guides by ID.

#### Listing Guides

You can list guides using either a GraphQL string or an array structure for field selection:

```php
// Using GraphQL string
$fields = <<<GQL
    guides {
        id
        title
        excerpt
        areas
        categories
        category_heading
        dates {
            end
            start
        }
        featuredmedia {
            sizes {
                full {
                    height
                    source_url
                    width
                }
            }
        }
    }
GQL;

$guides = $api->guides()->list([
    'per_page' => 5,
    'page' => 1,
    'lang' => 'sv'
], $fields);

// Using array structure
$guides = $api->guides()->list(
    [
        'per_page' => 5,
        'page' => 1,
        'lang' => 'sv'
    ],
    [
        'guides' => [
            'id',
            'title',
            'excerpt',
            'featuredmedia' => [
                'id',
                'sizes' => [
                    'full' => [
                        'height',
                        'width',
                        'source_url'
                    ]
                ]
            ]
        ]
    ]
);
```

#### Getting a Single Guide

Retrieve a specific guide by ID with related content:

```php
// Using GraphQL string
$fields = <<<GQL
    guide {
        id
        title
        excerpt
        areas
        categories
        category_heading
        dates {
            end
            start
        }
        featuredmedia {
            sizes {
                full {
                    height
                    source_url
                    width
                }
            }
        }
    }
    related {
        events {
            excerpt
            title
        }
        guides {
            excerpt
            title
        }
        places {
            excerpt
            title
        }
    }
GQL;

$guide = $api->guides()->getById(8337, 'sv', $fields);

// Using array structure
$guide = $api->guides()->getById(
    8337, 
    'sv',
    [
        'guide' => [
            'id',
            'title',
            'excerpt',
            'featuredmedia' => [
                'sizes' => [
                    'full' => [
                        'height',
                        'width',
                        'source_url'
                    ]
                ]
            ]
        ],
        'related' => [
            'events' => [
                'excerpt',
                'title'
            ],
            'guides' => [
                'excerpt',
                'title'
            ],
            'places' => [
                'excerpt',
                'title'
            ]
        ]
    ]
);
```

### Error Handling

The API uses exception handling for errors. Wrap your API calls in try-catch blocks:

```php
try {
    $guides = $api->guides()->list(['lang' => 'sv']);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

## Notes

- Empty fields are not allowed and will throw an `InvalidArgumentException`
- Related content is only available when retrieving a single guide using `getById`
- The API supports both GraphQL string and array structure for field selection
- All dates are in ISO 8601 format 