<?php
/**
 * Theme Routing Systems
 *
 * This theme implements two complementary routing systems:
 *
 * 1. Standard Template Routing (template-routing.php):
 *    - Customizes the WordPress template hierarchy
 *    - Organizes template files into a custom structure
 *    - Handles routing for regular WordPress pages, posts, archives, etc.
 *    - Can also handle AJAX requests to standard WordPress URLs
 *    - Utilizes a Model-View-Controller (MVC) structure
 *    - Leverages WordPress core functionality for query parameters, permalinks, etc.
 *    - Use for:
 *      - Custom template paths
 *      - Organizing templates into subdirectories
 *      - AJAX requests to existing WordPress routes (e.g., /blog)
 *      - Scenarios requiring full MVC architecture
 *      - When you need to work within WordPress's content model and querying system
 *
 *    Example: A POST request to /blog could be routed to a partial view for blog cards,
 *    utilizing WordPress's built-in query parameters and post data.
 *
 * 2. Virtual Routing (virtual-router.php):
 *    - Handles custom routes that don't correspond to actual WordPress pages/posts
 *    - Provides flexibility to go beyond WordPress core functionality
 *    - Ideal for simple API endpoints or custom functionality
 *    - Supports various HTTP methods (GET, POST, PUT, DELETE, etc.)
 *    - Can point to template routes for dynamic routing scenarios
 *    - Use for:
 *      - Custom API endpoints
 *      - Simple AJAX handling without need for full MVC structure
 *      - HTMX interactions
 *      - Unique use cases that don't fit well within WordPress's standard content model
 *      - When you need more control over request handling and response format
 *
 * Key Points:
 * - Template routing works within WordPress's core systems and is ideal for content-centric operations
 * - Virtual routing offers more flexibility and is recommended for unique use cases or when you need to
 *   step outside WordPress's standard functionality
 * - Template routing can handle AJAX, but within WordPress's paradigm
 * - Virtual routing is great for custom endpoints that don't map to WordPress content types
 * - Choose based on whether you need to leverage WordPress core or require more custom control
 * - Both systems can work together for flexible and powerful routing solutions
 *
 * Both systems are crucial for the theme's functionality. Use the appropriate
 * system based on your specific requirements, keeping in mind that they can
 * complement each other for more complex scenarios.
 */

// Standard template routing (leverages WordPress core functionality)
require_once get_template_directory() . '/inc/theme/template-routing.php';

// Virtual routing for custom endpoints and unique use cases
require_once get_template_directory() . '/inc/theme/virtual-router.php';
