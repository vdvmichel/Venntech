# Venntech - Perfex CRM Module

Venntech is a comprehensive module for Perfex CRM, meticulously designed to cater to businesses operating in the renewable energy sector, with a special focus on solar panel installations. This module extends Perfex CRM's capabilities to help manage products (including complex composite products), generate detailed estimates and quotations, oversee project templates and tasks, create inspection reports, and handle delivery documentation.

## Key Features

The Venntech module offers a suite of features to streamline operations for renewable energy businesses:

*   **Product Management:**
    *   Manage a detailed catalog of products.
    *   Support for "samengestelde producten" (composite products/product bundles).
    *   Store specific attributes for products, such as kilowatt-peak (kWp) and kilowatt-hour (kWh) for solar panels and batteries.
    *   Track inventory and pricing, including purchase price and recommended sales price.
*   **Estimates and Quotations:**
    *   Create and manage estimate templates (using `FEATURE_ESTIMATE_TEMPLATES`).
    *   Generate detailed quotations (using `FEATURE_ESTIMATE`) for clients, incorporating products and services.
    *   Calculate profit margins and apply discounts.
*   **Project Management:**
    *   Utilize project templates (using `FEATURE_PROJECT_TEMPLATES`) for standardized project workflows.
    *   Manage tasks (using `FEATURE_TAKEN`) associated with projects, with predefined task tags for common stages (e.g., installation, inspection, grid connection).
*   **Client Documentation:**
    *   Generate and manage inspection reports (using `FEATURE_INSPECTIE_RAPPORT`) with details about site conditions, electrical setup, and PV system information.
    *   Create opleverdocumenten (delivery/completion documents, using `FEATURE_OPLEVERDOCUMENT`) to formalize project handover.
*   **Calculations and Simulations:**
    *   Includes helpers for various calculations, potentially including payback period simulations (`venntech_calculator_helper`, `venntech_terugverdientijd_helper`).
*   **Scheduling:**
    *   Manage installation dates (using `FEATURE_PLAATSING_DATUM`).
*   **Customizable Settings:**
    *   Configure various module settings (using `FEATURE_SETTINGS`), including default profit margins, task assignments, and parameters for calculations.
*   **User Permissions:**
    *   Granular control over staff access to different module features.
*   **Multi-language Support:**
    *   Available in Dutch and English.

## Installation Steps

To install the Venntech module, follow these steps:

1.  **Prerequisites:** Ensure you have a working installation of Perfex CRM.
2.  **Download/Clone Module:** Obtain the Venntech module files. If you have a ZIP file, extract it.
3.  **Upload Module:**
    *   Navigate to the `modules` directory within your Perfex CRM installation path (e.g., `/path/to/perfex_crm/modules/`).
    *   Upload the entire `venntech` folder (containing all the module files and subdirectories) into this `modules` directory.
4.  **Activate Module:**
    *   Log in to your Perfex CRM admin panel.
    *   Go to "Setup" > "Modules".
    *   You should see "Venntech" listed among the available modules.
    *   Click the "Activate" button next to the Venntech module. This will trigger the installation script (`install.php`), which sets up the necessary database tables and default settings.
5.  **Verify Installation (Recommended):**
    *   Once activated, a new menu item "VENNTECH" should appear in the main sidebar.
    *   You can also check under "Setup" > "Settings" for any Venntech-specific settings to ensure the module is integrated.

## Usage Overview

Once installed and activated, the Venntech module integrates into your Perfex CRM admin interface.

*   **Main Menu:** Most of the Venntech module's functionalities can be accessed via the "VENNTECH" main menu item added to the Perfex CRM sidebar. This menu will typically have sub-items for:
    *   Estimates
    *   Estimate Templates
    *   Project Templates
    *   Composite Products
    *   Products
    *   Tasks
    *   Inspection Reports
    *   Delivery Documents
    *   Installation Dates
    *   Calculation/Simulation tools
    *   (Note: Exact sub-item names may vary based on the selected language.)
*   **Settings:** Module-specific settings can usually be found under "Setup" > "Settings" in the Perfex admin area, often within a dedicated "Venntech" tab or section. Here you can configure default values, API keys (if any), and other operational parameters.
*   **Workflow:** While the specific workflow can be tailored, a general business flow might involve:
    1.  Configuring products and composite products.
    2.  Setting up project and estimate templates.
    3.  Creating estimates for clients.
    4.  Converting estimates to projects and managing tasks.
    5.  Generating inspection and delivery documents upon project completion.
    *   The `venntech-business-flow.png` image included in the module provides a visual representation of a typical business process facilitated by this module.
*   **Permissions:** Access to Venntech features is controlled by Perfex CRM's staff roles and permissions. Ensure that relevant staff members have the necessary permissions assigned to view, create, edit, or delete Venntech-related records.

## Technical Stack/Dependencies

*   **Perfex CRM:** This module is an extension for Perfex CRM and requires Perfex CRM to be installed and operational.
*   **PHP:** Developed in PHP, leveraging the CodeIgniter framework (as Perfex CRM itself is built upon it), and compatible with the PHP version required by your Perfex CRM installation.
*   **MySQL:** Uses MySQL for database storage, as part of the Perfex CRM environment.
*   **Web Server:** Requires a web server (like Apache or Nginx) capable of running PHP applications.

## Languages

The Venntech module includes language files for:

*   **English**
*   **Dutch**

The module will respect the language settings of your Perfex CRM installation and the individual user's language preference if translations are available.
