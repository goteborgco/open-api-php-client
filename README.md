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
- Type safety with proper PHP classes for each entity type
- Simpler parameter handling
- Structured field selection
- Default values for common use cases
- Full IDE support with autocompletion

### Guides

The API provides methods to list guides and retrieve individual guides by ID. All responses are mapped to proper PHP classes for type safety.

#### Entity Types

The following entity types are available:

- `WpEntity`: Base entity for guides, events, and places
  - `getId(): int`
  - `getTitle(): ?string`
  - `getExcerpt(): ?string`
  - `getContent(): ?string`
  - `getFeaturedMedia(): Media[]`
  - `getLocation(): ?Location`
  - `getContact(): ?Contact`
  - `getDates(): EventDate[]`
  
- `Location`: Location information
  - `getAddress(): ?string`
  - `getLat(): ?float`
  - `getLng(): ?float`
  - `getName(): ?string`
  
- `Media`: Media assets
  - `getId(): int`
  - `getCaption(): ?string`
  - `getAltText(): ?string`
  - `getSizes(): ?MediaSizes`
  
- `MediaSizes`: Image size variants
  - `getFull(): ?Image`
  - `getLarge(): ?Image`
  - `getMedium(): ?Image`
  - `getThumbnail(): ?Image`
  
- `Image`: Individual image properties
  - `getWidth(): int`
  - `getHeight(): int`
  - `getSourceUrl(): string`

#### Listing Guides

You can list guides using either a GraphQL string or an array structure for field selection. Both approaches will return strongly-typed `WpEntity` objects.

```php
// Using GraphQL string
$fields = <<<GQL
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
);

// Work with the returned typed objects
foreach ($guides as $guide) {
    // All properties are properly typed
    echo $guide->getTitle();
    echo $guide->getExcerpt();
    
    // Access nested objects with type safety
    $location = $guide->getLocation();
    if ($location) {
        echo $location->getAddress();
        echo sprintf('Coordinates: %f, %f', $location->getLat(), $location->getLng());
    }
    
    // Work with media and images
    foreach ($guide->getFeaturedMedia() as $media) {
        $sizes = $media->getSizes();
        if ($sizes && $full = $sizes->getFull()) {
            echo $full->getSourceUrl();
            echo sprintf('Size: %dx%d', $full->getWidth(), $full->getHeight());
        }
    }
    
    // Handle dates
    foreach ($guide->getDates() as $date) {
        echo sprintf('From %s to %s', $date->getStart(), $date->getEnd());
    }
}
```

#### Getting a Single Guide

Similarly, you can retrieve a specific guide using either query format:

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

$result = $api->guides()->getById(8337, 'sv', $fields);

// Using array structure
$result = $api->guides()->getById(
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

// Work with the returned typed objects
$guide = $result->guide;
echo $guide->getTitle();

// Access contact information
if ($contact = $guide->getContact()) {
    echo $contact->getEmail();
    echo $contact->getPhone();
    echo $contact->getWebsite();
}

// Access related content
if ($related = $result->related) {
    // Related guides
    foreach ($related->getGuides() as $relatedGuide) {
        echo $relatedGuide->getTitle();
    }
    
    // Related events
    foreach ($related->getEvents() as $event) {
        echo $event->getTitle();
        foreach ($event->getDates() as $date) {
            echo sprintf('%s - %s', $date->getStart(), $date->getEnd());
        }
    }
    
    // Related places
    foreach ($related->getPlaces() as $place) {
        echo $place->getTitle();
        if ($location = $place->getLocation()) {
            echo $location->getAddress();
        }
    }
}
```

### Error Handling

The API uses exception handling for errors. Wrap your API calls in try-catch blocks:

```php
try {
    $guides = $api->guides()->list(['lang' => 'sv']);
} catch (\InvalidArgumentException $e) {
    // Handle validation errors (e.g., empty fields)
    echo "Validation Error: " . $e->getMessage();
} catch (\Exception $e) {
    // Handle API errors
    echo "API Error: " . $e->getMessage();
}
```

## Notes

- Empty fields are not allowed and will throw an `InvalidArgumentException`
- Related content is only available when retrieving a single guide using `getById`
- The API supports both GraphQL string and array structure for field selection
- All dates are in ISO 8601 format
- All entity classes provide proper type hints for IDE support
- Nested objects are automatically mapped to their corresponding classes 