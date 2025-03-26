# Göteborg & Co API Client for PHP

Official PHP client library for interacting with the Göteborg & Co GraphQL API. This package provides a type-safe interface for accessing Göteborg & Co's API, offering both structured methods for common operations and flexible GraphQL query capabilities.

## Features

- Type-safe methods for common operations (guides, events, places, search and taxonomies)
- Direct GraphQL query support for advanced use cases
- Built-in error handling and response parsing
- Comprehensive documentation and examples

## Installation

Install the package via composer:

```bash
composer require goteborgco/open-api-php-client
```

## Setup

Initialize the API Client with your API URL and subscription key:

```php
use GBGCO\API;

$apiUrl = 'https://apim-openapi-gbgco-prod.azure-api.net/gql';
$subscriptionKey = 'your-subscription-key';

$api = new API($apiUrl, $subscriptionKey);
```

## Usage

### Type-Specific Methods

While the general query method provides full flexibility, the API also includes type-specific methods for common operations. These methods provide:
- Type safety with proper PHP classes for each entity type
- Simpler parameter handling
- Structured field selection
- Default values for common use cases
- Full IDE support with autocompletion

#### Entity Types

The following entity types are available:

- `WpEntity`: Base entity for guides, events, and places
  - `getId(): int` - Get the WordPress post ID
  - `getDate(): ?string` - Get the creation date in ISO 8601 format
  - `getModified(): ?string` - Get the last modification date in ISO 8601 format
  - `getType(): ?string` - Get the content type (e.g., 'event', 'place', 'guide')
  - `getLink(): ?string` - Get the public URL of the content item
  - `getTitle(): ?string` - Get the content title
  - `getContent(): ?string` - Get the main content (may contain HTML)
  - `getExcerpt(): ?string` - Get a brief summary of the content
  - `getCategories(): array` - Get array of category IDs
  - `getAreas(): array` - Get array of area IDs
  - `getTags(): array` - Get array of tag IDs
  - `getInvisibleTags(): array` - Get array of invisible tag IDs (for internal use)
  - `getLang(): string` - Get the language code ('en'|'sv')
  - `getTranslations(): ?Translations` - Get available translations
  - `getFeaturedMedia(): Media[]` - Get array of featured media objects
  - `getCategoryHeading(): ?string` - Get optional category heading
  - `getGallery(): Media[]` - Get array of gallery media objects
  - `getContact(): ?Contact` - Get contact information
  - `getLocation(): ?Location` - Get location information
  - `isFree(): ?bool` - Check if admission is free (for events)
  - `getDates(): EventDate[]` - Get array of event dates
  - `getPlaceId(): ?int` - Get associated place ID (for events)
  - `getClassification(): ?int` - Get classification value
  - `getCurrentInTime(): ?CurrentInTime` - Get temporal availability information

- `Location`: Location information
  - `getAddress(): ?string` - Get complete address string
  - `getLat(): ?float` - Get latitude coordinate
  - `getLng(): ?float` - Get longitude coordinate
  - `getZoom(): ?int` - Get default map zoom level
  - `getPlaceId(): ?string` - Get Google Places ID
  - `getName(): ?string` - Get location/establishment name
  - `getStreetNumber(): ?string` - Get street number
  - `getStreetName(): ?string` - Get street name
  - `getState(): ?string` - Get state/region
  - `getPostCode(): ?string` - Get postal code
  - `getCountry(): ?string` - Get full country name
  - `getCountryShort(): ?string` - Get two-letter country code (ISO 3166-1 alpha-2)

- `Media`: Media assets
  - `getId(): int` - Get media item ID
  - `getCredit(): ?string` - Get attribution information
  - `getCaption(): ?string` - Get media description
  - `getAltText(): ?string` - Get alternative text for accessibility
  - `getMediaType(): ?string` - Get type of media (e.g., 'image', 'video')
  - `getMimeType(): ?string` - Get MIME type of the media file
  - `getSizes(): ?MediaSizes` - Get available size variations

- `MediaSizes`: Image size variants
  - `getMedium(): ?Image` - Get medium size version
  - `getThumbnail(): ?Image` - Get thumbnail version
  - `getLarge(): ?Image` - Get large size version
  - `getFull(): ?Image` - Get full/original size version

- `Image`: Individual image properties
  - `getWidth(): int` - Get width in pixels
  - `getHeight(): int` - Get height in pixels
  - `getSourceUrl(): string` - Get URL where the image can be accessed

- `Contact`: Contact information
  - `getEmail(): ?string` - Get contact email address
  - `getPhone(): ?string` - Get contact phone number
  - `getWebsite(): ?string` - Get website URL
  - `getFacebook(): ?string` - Get Facebook profile/page URL
  - `getInstagram(): ?string` - Get Instagram profile URL

- `EventDate`: Date range for events
  - `getStart(): ?string` - Get start date in ISO 8601 format
  - `getEnd(): ?string` - Get end date in ISO 8601 format

- `CurrentInTime`: Temporal availability information
  - `getMonths(): array` - Get array of month numbers (1-12) when item is active
  - `getWeekdays(): array` - Get array of weekday numbers (0-6) when item is active

- `Features`: GeoJSON Feature
  - `getType(): string` - Get GeoJSON object type
  - `getGeometry(): Geometry` - Get geometric data
  - `getProperties(): Properties` - Get feature properties

- `Geometry`: GeoJSON geometry
  - `getType(): string` - Get geometry type (e.g., 'Point', 'LineString', 'Polygon')
  - `getCoordinates(): array` - Get coordinates in GeoJSON format
  - `getLatitude(): ?float` - Get latitude (for Point geometries)
  - `getLongitude(): ?float` - Get longitude (for Point geometries)

- `Properties`: GeoJSON feature properties
  - `getName(): string` - Get feature display name
  - `getId(): int` - Get unique identifier
  - `getIcon(): string` - Get icon URL/identifier
  - `getThumbnail(): string` - Get thumbnail image URL
  - `getType(): string` - Get feature classification type
  - `getSlug(): string` - Get URL-friendly identifier

- `Markers`: GeoJSON FeatureCollection
  - `getType(): string` - Get GeoJSON object type (typically 'FeatureCollection')
  - `getFeatures(): Features[]` - Get array of Feature objects

- `Related`: Related content collection
  - `getPlaces(): WpEntity[]` - Get array of related place entities
  - `getGuides(): WpEntity[]` - Get array of related guide entities
  - `getEvents(): WpEntity[]` - Get array of related event entities

- `Translations`: Content translations
  - `getSv(): ?int` - Get ID of Swedish content version
  - `getEn(): ?int` - Get ID of English content version

#### Listing Guides

List guides using GraphQL field selection:

```php
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

$guides = $api->guides()->list(
    [
        'per_page' => 5,
        'page' => 1,
        'lang' => 'sv'
    ],
    $fields
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

Retrieve a specific guide:

```php
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
    foreach ($related->getGuides() as $relatedGuide) {
        echo $relatedGuide->getTitle();
    }
    
    foreach ($related->getEvents() as $event) {
        echo $event->getTitle();
    }
    
    foreach ($related->getPlaces() as $place) {
        echo $place->getTitle();
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

### Places

The API provides methods to list places and retrieve individual places by ID. All responses are mapped to proper PHP classes for type safety.

#### Listing Places

List places with field selection:

```php
$fields = <<<GQL
    places {
        id
        title
        excerpt
        categories
        areas
        tags
    }
    markers {
        features {
            geometry {
                coordinates
                type
            }
            properties {
                icon
                id
                name
                slug
                type
            }
            type
        }
        type
    }
GQL;

$result = $api->places()->list(
    [
        'lang' => 'sv',
        'categories' => [10],
        'areas' => [20],
        'tags' => [30],
        'per_page' => 5,
        'page' => 1
    ],
    $fields
);

// Access the typed results
foreach ($result->items as $place) {
    echo $place->getTitle();
    echo $place->getExcerpt();
}

// Work with map markers
if ($result->markers) {
    foreach ($result->markers->getFeatures() as $feature) {
        $geometry = $feature->getGeometry();
        $properties = $feature->getProperties();
        
        echo sprintf(
            'Location: %s at coordinates [%s]',
            $properties->getName(),
            implode(', ', $geometry->getCoordinates())
        );
    }
}
```

#### Getting a Single Place

```php
$fields = <<<GQL
    place {
        id
        title
        excerpt
        content
        areas
        categories
    }
    markers {
        features {
            geometry {
                coordinates
                type
            }
            properties {
                id
                name
                icon
            }
            type
        }
    }
    related {
        events {
            title
            excerpt
        }
        guides {
            title
            excerpt
        }
        places {
            title
            excerpt
        }
    }
GQL;

$result = $api->places()->getById(1234, 'sv', $fields);

// Access the place data
$place = $result->place;
echo $place->getTitle();

// Access location information
if ($location = $place->getLocation()) {
    echo $location->getAddress();
    echo sprintf('Coordinates: %f, %f', $location->getLat(), $location->getLng());
}
```

### Events

The API provides methods to list events and retrieve individual events by ID. Events include support for date filtering and sorting.

#### Listing Events

```php
$fields = <<<GQL
    events {
        id
        title
        excerpt
        date
        categories
        areas
        tags
        category_heading
    }
    markers {
        features {
            geometry {
                coordinates
                type
            }
            properties {
                id
                name
                icon
            }
            type
        }
    }
GQL;

$result = $api->events()->list(
    [
        'lang' => 'sv',
        'categories' => [10],
        'areas' => [20],
        'tags' => [30],
        'start' => '2024-01-01',
        'end' => '2024-12-31',
        'free' => 1,
        'per_page' => 5,
        'page' => 1
    ],
    [
        'fields' => ['title', 'date'],
        'orders' => ['desc']
    ],
    $fields
);

// Access the events
foreach ($result->events as $event) {
    echo $event->getTitle();
    echo $event->getDate();
}
```

#### Getting a Single Event

```php
$fields = <<<GQL
    event {
        id
        title
        excerpt
        date
        areas
        categories
        category_heading
        tags
    }
    markers {
        features {
            geometry {
                coordinates
                type
            }
            properties {
                id
                name
                icon
            }
            type
        }
    }
    related {
        events {
            title
            excerpt
        }
        guides {
            title
            excerpt
        }
        places {
            title
            excerpt
        }
    }
GQL;

$result = $api->events()->getById(2608, 'sv', $fields);

// Access the event data
$event = $result->event;
echo $event->getTitle();
echo $event->getDate();
```

### Search

```php
$fields = <<<GQL
    results {
        id
        title
        excerpt
        date
        categories
        areas
        tags
        featuredmedia {
            caption
            credit
            id
            media_type
        }
    }
GQL;

$results = $api->search()->query(
    filter: [
        'query' => 'Fika',  // Required search term
        'lang' => 'sv'      // Optional language filter (enum: 'en' or 'sv')
    ],
    sortBy: [
        'fields' => 'title',
        'orders' => 'desc'
    ],
    fields: $fields
);

// Work with search results
foreach ($results->results as $item) {
    echo $item->getTitle();
    echo $item->getExcerpt();
}
```

### Taxonomies

The API provides methods to list all available taxonomies. This is useful for getting an overview of all taxonomy types in the system.

```php
$fields = <<<GQL
    name
    description
    value
    types
GQL;

$taxonomies = $api->taxonomies()->list(
    ['lang' => 'sv'],
    $fields
);

// Work with taxonomy results
foreach ($taxonomies as $taxonomy) {
    echo $taxonomy->getName();
    echo $taxonomy->getDescription();
    echo $taxonomy->getValue();
    
    // Access available types
    foreach ($taxonomy->getTypes() as $type) {
        echo $type;
    }
}
```

### Taxonomy

The API provides methods to list all terms within a specific taxonomy. Terms can be retrieved either as a flat list or as a hierarchical tree structure based on parent-child relationships.

#### Listing Taxonomy Terms (Flat)

```php
$fields = <<<GQL
    id
    name
    count
    description
    parent
GQL;

$terms = $api->taxonomy()->list(
    taxonomyName: 'categories',
    filter: ['lang' => 'sv'],
    fields: $fields
);

// Work with taxonomy terms
foreach ($terms as $term) {
    echo $term->getId();
    echo $term->getName();
    echo $term->getCount();
    echo $term->getDescription();
    
    if ($term->getParent() !== null) {
        echo "Parent ID: " . $term->getParent();
    }
}
```

#### Listing Taxonomy Terms (Hierarchical)

When working with hierarchical taxonomies like categories, you can retrieve terms in a tree structure:

```php
$fields = <<<GQL
    id
    name
    count
    description
    parent
GQL;

$tree = $api->taxonomy()->list(
    taxonomyName: 'categories',
    filter: ['lang' => 'sv'],
    fields: $fields,
    hierarchical: true
);

// Work with hierarchical structure
foreach ($tree as $node) {
    $term = $node['term'];
    echo $term->getName() . '<br>';
    
    // Process children
    foreach ($node['children'] as $childNode) {
        $childTerm = $childNode['term'];
        echo "- " . $childTerm->getName() . '<br>';
        
        // Process grandchildren
        foreach ($childNode['children'] as $grandchildNode) {
            echo "-- " . $grandchildNode['term']->getName() . '<br>';
        }
    }
}
```

## Notes

- Empty fields are not allowed and will throw an `InvalidArgumentException`
- Related content is only available when retrieving a single guide using `getById`
- All dates are in ISO 8601 format
- All entity classes provide proper type hints for IDE support
- Nested objects are automatically mapped to their corresponding classes

## Array Structure for Fields

As an alternative to GraphQL strings, you can also specify fields using an array structure. This can be useful when building queries dynamically:

```php
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
```

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

This array structure will be automatically converted to the equivalent GraphQL fields string internally. 