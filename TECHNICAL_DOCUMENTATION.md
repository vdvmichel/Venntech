# Venntech Module - Technical Documentation

This document provides a detailed description of the controllers and models that make up the Venntech module for Perfex CRM. It is intended for developers or users who want a deeper understanding of the module's internal structure and data handling.

## Controllers

### `Berekening_simulatie.php` (Calculation Simulation Controller)

*   **Purpose:** Manages the user interface and backend logic for a payback period or return on investment simulation for solar panel installations. It allows users to select various components (solar panels, inverters, batteries, etc.) and input parameters to calculate potential returns.
*   **Key Methods:**
    *   `index()`: Displays the main simulation form, loading necessary product data (solar panels, inverters, batteries, placement options, structure types) from `product_model` and default calculation parameters from system options.
    *   `berekening()`: An AJAX endpoint that receives user inputs from the simulation form (via POST). It utilizes the `Terugverdientijd` helper class to perform the actual calculation based on the selected components and parameters. The results are returned as a JSON response.
*   **Models Used:** `Product_model`.
*   **Helpers Used:** `Terugverdientijd` (likely a custom helper for payback calculations).
*   **Permissions:** Checks for `FEATURE_BEREKENING` view permission.

### `Estimate_pdf_layouts.php` (Estimate PDF Layouts Controller)

*   **Purpose:** Manages the creation and editing of customizable PDF layouts for estimate documents. This allows administrators to define standard content that appears before and after the main items list in generated estimate PDFs.
*   **Key Methods:**
    *   `index()`: Displays an overview page related to estimate PDF layouts (likely listing estimate templates for which layouts can be defined or edited).
    *   `edit($estimate_template_id)`:
        *   **POST:** Saves the PDF layout content for a specific estimate template. It handles up to five "pre-pages" (content before the items) and five "post-pages" (content after the items). The content is HTML and is saved using `htmlspecialchars`.
        *   **GET:** Prepares and displays the form for editing the PDF layout of a given estimate template. It retrieves existing layout data (or initializes empty fields if no layout exists) and makes available Perfex CRM's standard merge fields for use in the layout content.
*   **Models Used:** `Project_template_model`, `Estimate_template_model`, `Estimate_template_element_model`, `Estimate_template_items_model`, `Estimate_pdf_layout_model`, `Estimates_extra_model`.
*   **Permissions:** Checks for `FEATURE_ESTIMATE_TEMPLATES` view and edit permissions.

### `Estimate_template_elements.php` (Estimate Template Elements Controller)

*   **Purpose:** Manages the individual components or sections (referred to as "elements") within an estimate template. These elements group various types of items (products, composite products, or item groups from Perfex's invoicing system) that can be added to an estimate template, allowing for a structured and modular approach to creating reusable estimate blueprints.
*   **Key Methods:**
    *   `index()`: Displays a view related to estimate templates, likely serving as an entry point to manage their elements.
    *   `table()`: Provides data for a server-side datatable, probably listing estimate templates.
    *   `edit($estimate_template_id, $id = '')`: Handles the creation or modification of an element within a specified estimate template.
        *   **POST:** Saves the element's name and its associated line items. It can link an element to specific products (`items`), composite products (`samengestelde_product`), or general item groups (`groups`). It also allows specifying if the quantity of these items should be multiplied (e.g., by the number of panels).
        *   **GET:** Displays the form for adding or editing an element. It loads the parent estimate template, the element itself (if editing), and all associated items. It also provides selection options for available products, composite products, and item groups. It checks if an element is already in use in an actual estimate to disable modifications if necessary.
    *   `delete($id = '')`: Deletes an estimate template element if it's not currently referenced in any existing estimates. It also removes associated item links.
*   **Models Used:** `Estimate_template_model`, `Estimate_template_element_model`, `Estimate_template_items_model`, `Estimates_extra_items_model`, `Project_template_model`, `Product_model`, `Samengestelde_product_model`, `Invoice_items_model`.
*   **Permissions:** Checks for `FEATURE_PROJECT_TEMPLATES` (potentially should be `FEATURE_ESTIMATE_TEMPLATES`) and `FEATURE_ESTIMATE_TEMPLATES` for create, edit, view, delete operations.

### `Estimate_templates.php` (Estimate Templates Controller)

*   **Purpose:** Manages the overall estimate templates. These templates act as master copies or blueprints for creating new estimates, containing predefined structures, elements, and associated items. This facilitates quick and consistent quotation generation.
*   **Key Methods:**
    *   `index()`: Displays the main list view for all estimate templates.
    *   `table()`: Provides data for a server-side datatable that lists the estimate templates.
    *   `table_elements($estimate_template_id)`: Provides data for a server-side datatable listing all elements associated with a specific estimate template. This is typically used within the estimate template edit view.
    *   `edit($id = '')`: Handles the creation or modification of an estimate template.
        *   **POST:** Saves the basic details of the estimate template, such as its name and the ID of an associated project template.
        *   **GET:** Displays the form for adding a new estimate template or editing an existing one. It allows users to define the template's name and link it to a project template. The view also typically includes the interface to manage the template's elements (added via `Estimate_template_elements` controller functionality).
    *   `delete($id = '')`: Deletes an estimate template, but only if it's not currently referenced in any existing estimates (`estimates_extra_model`). If deletable, it also removes all associated elements and their items.
*   **Models Used:** `Project_template_model`, `Estimate_template_model`, `Estimate_template_element_model`, `Estimate_template_items_model`, `Estimates_extra_model`.
*   **Permissions:** Checks for `FEATURE_ESTIMATE_TEMPLATES` for create, edit, view, delete operations.

### `Inputparameters.php` (Input Parameters Controller)

*   **Purpose:** Manages a settings page for configuring various numerical input parameters that are likely used in calculations throughout the module, particularly for financial simulations, energy yield estimations, and pricing.
*   **Key Methods:**
    *   `index()`:
        *   **POST:** Saves a wide range of parameters (e.g., `venntech_bebat_batterij`, `venntech_unit_prijs_per_panel`, `venntech_vollasturen`, various premium and tariff rates, self-consumption percentages, `hespul_waarde`) as system options using Perfex's `update_option()` function.
        *   **GET:** Retrieves the current values of these parameters using `get_option()` and passes them to the `inputparameters_view` for display and editing.
    *   `voor_met_batterij()`, `voor_zonder_batterij()`, `na_met_batterij()`, `na_zonder_batterij()`: These appear to be intended as calculation helper methods based on the input parameters but seem incomplete or non-functional in their current state within the controller, as they use undefined local variables for calculations.
*   **Models Used:** `Staff_model` (loaded but not visibly used in core logic), `Settings_taak_model` (loaded but not visibly used), `Project_template_tasks_model` (loaded but not visibly used). Primarily interacts with Perfex CRM's options system (`get_option`, `update_option`).
*   **Permissions:** Checks for `FEATURE_SETTINGS` for view and edit access.

### `Inspectie_rapporten.php` (Inspection Reports Controller)

*   **Purpose:** Manages the lifecycle of inspection reports, including their creation, editing, data input across various categories, image attachments, and deletion. These reports are comprehensive site assessments.
*   **Key Methods:**
    *   `index()`: Displays the main list of inspection reports.
    *   `table()`: Provides data for the server-side datatable listing inspection reports.
    *   `edit($id = '')`:
        *   **POST:** Saves all data related to an inspection report. This includes general information (`pc_inspectie_rapport`, `pc_inspectie_rapport_algemeen`), electrical details (`pc_inspectie_rapport_elektriciteit`), roof information (`pc_inspectie_rapport_info_dak`), and PV system specifics (`pc_inspectie_rapport_info_pv`). Can also mark an associated Perfex task as complete.
        *   **GET:** Prepares and displays the detailed inspection report form, loading existing data for editing or initializing fields for a new report. It populates dropdowns with predefined options using helper functions.
    *   `upload($rapportid = '')`: Handles AJAX image uploads for a specific inspection report, storing files and creating records in `pc_inspectie_rapport_image`.
    *   `delete($id = '')`: Deletes an entire inspection report, including all its data across related tables and associated uploaded images from the server.
    *   `delete_image()`: Deletes a single image associated with an inspection report.
*   **Models Used:** `Inspectie_rapport_model`, `Inspectie_rapport_algemeen_model`, `Inspectie_rapport_elektriciteit_model`, `Inspectie_rapport_info_dak_model`, `Inspectie_rapport_info_pv_model`, `Inspectie_rapport_image_model`, `Tasks_model`, `Taken_model`.
*   **Permissions:** Checks for `FEATURE_INSPECTIE_RAPPORT` for view, create, edit, and delete operations.

### `Offertes.php` (Quotations Controller)

*   **Purpose:** Manages a specialized and advanced quotation (offerte) creation and editing process. It extends Perfex CRM's native estimate functionality by integrating deeply with Venntech's own templating system, product definitions, and complex pricing logic.
*   **Key Methods:**
    *   `index()`: Displays the primary view for managing these specialized Venntech estimates.
    *   `table()`: Provides data for the server-side datatable listing these estimates.
    *   `edit($id = '')`: The core method for creating or editing a Venntech estimate.
        *   **POST:** Processes the submitted estimate data. This involves:
            1.  Creating/updating a base Perfex estimate record.
            2.  Creating/updating a `pc_estimates_extra` record to store Venntech-specifics (template used, detailed panel info, salesperson).
            3.  Calling `update_estimate_itemables()` to calculate and save all line items based on the selected Venntech estimate template, quantities, profit margins, and also adds "meerwerken" (additional works) and environmental contributions. This method is responsible for populating `tblitemable` and `tblitem_tax` for the Perfex estimate.
            4.  Calling `update_customfieldsvalues()` to populate Perfex custom fields linked to the estimate with Venntech-specific data.
            5.  Optionally sending the estimate to the client.
        *   **GET:** Prepares the form for creating or editing an estimate. It loads client data, Venntech estimate templates, tax rates, discount types, and consumable materials for selection.
    *   `estimate_template_items_html()`: An AJAX endpoint that dynamically generates HTML for the item selection part of the estimate form, based on the structure of the chosen Venntech estimate template.
    *   `update_estimate_itemables(...)`: A complex helper method that translates the Venntech template structure and user selections into Perfex estimate line items, applying business logic for pricing, quantities, and discounts.
    *   `update_customfieldsvalues(...)`: Synchronizes data from `pc_estimates_extra` to standard Perfex custom fields.
    *   `get_grouped()`: Retrieves active products/items, grouped by their category, for use in selection dropdowns.
*   **Models Used:** An extensive list including `Estimates_extra_items_model`, `Estimates_extra_meerwerken_model`, `Estimates_extra_model`, `Estimate_template_model`, `Estimate_template_element_model`, `Estimate_template_items_model`, `Taxes_model`, `Estimates_model`, `Currencies_model`, `Clients_model`, `Invoice_items_model`, `Samengestelde_product_items_model`, `Samengestelde_product_model`, `Product_model`, `Type_kortingen_model`.
*   **Permissions:** Checks for `FEATURE_ESTIMATE` for view, create, and edit operations.

### `Opleverdocumenten.php` (Delivery Documents Controller)

*   **Purpose:** Manages delivery or completion documents (`opleverdocumenten`). These documents formalize the handover of a completed installation, capturing details about the installation, materials used, staff involved, and photographic evidence.
*   **Key Methods:**
    *   `index()`: Displays the main list of delivery documents.
    *   `table()`: Provides data for the server-side datatable listing delivery documents.
    *   `table_verbruiksmateriaal($id = '')`: Provides data for a datatable listing consumable materials used in a specific delivery document.
    *   `edit($id = '')`: Handles creation or editing of a delivery document.
        *   **POST:** If creating, it first calls a global helper `create_default_oplever_document()` to set up a basic structure, then redirects to the edit view. If editing, it saves data to `pc_opleverdocumenten` (main details), `pc_opleverdocument_algemeen` (general info, staff), and `pc_opleverdocument_installatie` (technical installation details). Can also mark an associated Perfex task as complete.
        *   **GET:** Prepares and displays the delivery document form, loading existing data or initializing fields. Populates dropdowns with predefined options.
    *   `upload($documentid = '')`: Handles AJAX image uploads for the delivery document, storing files and records in `pc_opleverdocument_fotos`.
    *   `delete($id = '')`: Deletes a delivery document and all its associated data and images.
    *   `delete_image()`: Deletes a single image associated with a delivery document.
*   **Models Used:** `Opleverdocument_model`, `Opleverdocument_algemeen_model`, `Opleverdocument_installatie_model`, `Opleverdocument_fotos_model`, `Tasks_model`, `Taken_model`.
*   **Permissions:** Checks for `FEATURE_OPLEVERDOCUMENT` for view, create, edit, and delete operations.

### `Opleverdocumenten_verbruiksmaterialen.php` (Delivery Document Consumables Controller)

*   **Purpose:** Manages the consumable materials (e.g., small installation parts) associated with a specific delivery document (`opleverdocument`). This controller likely provides an interface, possibly within the main delivery document view, to list, add, and remove these items.
*   **Key Methods:**
    *   `index()`: Displays a general view related to consumables for delivery documents.
    *   `view_table($opleverdocument_id)`: Displays a view specifically for managing consumables of a particular delivery document, loading available consumable products.
    *   `table($id = '')`: Provides data for a server-side datatable listing the consumable items (description, quantity, total price) linked to the specified delivery document ID (`$id`).
    *   `add()` / `add_item()`: Handles adding a new consumable item (linking a product from the 'verbruiksmateriaal' group with a quantity) to a delivery document. `add_item` is likely for AJAX requests.
    *   `delete($id = '')`: Removes a specific consumable item entry from a delivery document.
*   **Models Used:** `Opleverdocument_model`, `Opleverdocument_verbruiksmaterialen_model`, `Product_model`.
*   **Permissions:** Checks for `FEATURE_OPLEVERDOCUMENT` for view and delete operations.

### `Plaatsing_datums.php` (Installation Dates Controller)

*   **Purpose:** Manages the scheduling of installation dates, allowing association with clients, assignment to staff members, and linking to tasks. It also includes a calendar view for staff assignments.
*   **Key Methods:**
    *   `index()`: Displays the primary view for managing installation dates.
    *   `table()`: Provides data for a server-side datatable listing scheduled installation dates.
    *   `edit($id = '')`: Handles the creation or modification of an installation date record.
        *   **POST:** Saves the installation date details (client, staff, date, associated task). Can also mark the linked task as complete.
        *   **GET:** Prepares the form for scheduling an installation date, loading staff members and, if editing, the existing record and task details. Includes assets for a calendar display.
    *   `get_calendar_data()`: An AJAX endpoint that fetches tasks assigned to a specific staff member within a date range, formatted for display on a FullCalendar-like interface.
    *   `delete($id = '')`: Intended to delete an installation date record. **Note:** The current implementation incorrectly calls `taken_model->delete($id)` and redirects to the tasks list, which seems to be an error; it should likely use `plaatsing_datum_model->delete($id)` and redirect to the placement dates list.
*   **Models Used:** `Plaatsing_datum_model`, `Utilities_model`, `Tasks_model`, `Taken_model`.
*   **Permissions:** Checks for `FEATURE_PLAATSING_DATUM` for view, create, edit, and delete operations.

### `Producten.php` (Products Controller)

*   **Purpose:** Manages the products for the Venntech module. This extends Perfex CRM's standard item functionality by adding a related record in a custom table (`pc_items_extra`) to store additional, business-specific details (e.g., kWp, kWh, weight, technical descriptions, images).
*   **Key Methods:**
    *   `index()`: Displays the main list of all Venntech products.
    *   `table()`: Provides data for the server-side datatable listing the products.
    *   `edit($id = '')`: Handles creating a new product or editing an existing one.
        *   **POST:** When creating, it first adds a standard Perfex item (`invoice_items_model->add()`). A hook (presumably) creates an initial `pc_items_extra` record, which is then immediately updated by this controller with the Venntech-specific fields. When editing, it updates both the Perfex item and the `pc_items_extra` record. Handles product image uploads.
        *   **GET:** Prepares the product form. It loads item groups (categories) and, if editing, fetches data from both the standard item table and the `pc_items_extra` table.
    *   `change_product_status($id, $status)`: An AJAX endpoint to toggle the active/inactive status of a product (in `pc_items_extra`).
    *   `delete($id = '')`: Deletes a product. This action removes the record from Perfex's items table (which should cascade or be hooked to delete the `pc_items_extra` record) and also deletes any associated product image from the server.
*   **Models Used:** `Invoice_items_model` (for Perfex items), `Product_model` (for Venntech extended product data).
*   **Permissions:** Checks for `FEATURE_PRODUCTEN` for view, create, edit, and delete operations.

### `Project_templates.php` (Project Templates Controller)

*   **Purpose:** Manages project templates, which allow defining a standardized list of tasks and their order for various types of projects. This helps in quickly setting up new projects with a consistent structure.
*   **Key Methods:**
    *   `index()`: Displays the main list of project templates.
    *   `table()`: Provides data for a server-side datatable listing project templates.
    *   `edit($id = '')`: Handles creating or editing a project template.
        *   **POST:** Saves the template's name and description. If creating a new template, it also initializes a default set of tasks for it by copying predefined task configurations from `pc_instellingen_taak` into `pc_project_template_tasks`.
        *   **GET:** Prepares the project template form. It loads the global task settings (`instellingen_taken`) and, if editing, the specific template details and its configured tasks. The view allows for enabling/disabling and reordering tasks within the template.
    *   `delete($id = '')`: Deletes a project template and all its associated task configurations from `pc_project_template_tasks`.
    *   `change_task_status($id, $status)`: An AJAX endpoint to toggle the enabled/disabled status of a specific task within a project template.
    *   `task_order_up($template_id, $id)` / `task_order_down($template_id, $id)`: Handles reordering of tasks within a project template by adjusting their `task_order` values.
*   **Models Used:** `Settings_taak_model`, `Project_template_model`, `Project_template_tasks_model`.
*   **Permissions:** Checks for `FEATURE_PROJECT_TEMPLATES` for view, create, edit, and delete operations.

### `Samengestelde_producten.php` (Composite Products Controller)

*   **Purpose:** Manages composite products, which are bundles or kits created from multiple individual products. This allows selling a package of items as a single unit.
*   **Key Methods:**
    *   `index()`: Displays the main list of all composite products.
    *   `table()`: Provides data for the server-side datatable listing composite products.
    *   `edit($id = '')`: Handles the creation or editing of a composite product.
        *   **POST:** Saves the composite product's name and description to `pc_samengestelde_product`. It also manages the list of individual items (products) that form the composite product, storing these associations in `pc_samengestelde_product_items`. When editing, it replaces the existing list of items with the new one. (Note: A call to `handle_venntech_product_upload` seems out of place as composite products don't typically have their own images distinct from their components).
        *   **GET:** Prepares the form for adding or editing a composite product. It loads available individual products for selection as components. (Note: There's a potential issue in loading items for an existing composite product during edit, related to `array_get_by_index(1, $samengestelde_product_item)` which might not work as intended.)
    *   `change_samengestelde_product_status($id, $status)`: An AJAX endpoint to toggle the active/inactive status of a composite product.
    *   `delete($id = '')`: Deletes a composite product and removes all its associated item links from `pc_samengestelde_product_items`.
*   **Models Used:** `Samengestelde_product_model`, `Samengestelde_product_items_model`, `Product_model` (for fetching individual products).
*   **Permissions:** Checks for `FEATURE_SAMENGESTELDE_PRODUCTEN` for view, create, edit, and delete operations.

### `Settings.php` (General Settings Controller)

*   **Purpose:** Provides an interface for managing general settings specific to the Venntech module.
*   **Key Methods:**
    *   `index()`:
        *   **POST:** Saves global module settings, specifically the default staff member to assign inspection report tasks to (`assign_task_inspectie_rapport_staffid`) and the default margin of profit (`venntech_margin_of_profit`). These are stored as Perfex options.
        *   **GET:** Retrieves the current values for these settings and loads a list of staff members for the assignment dropdown. Displays the `settings_view`.
*   **Models Used:** `Staff_model`. Interacts primarily with Perfex CRM's options system (`get_option`, `update_option`).
*   **Permissions:** Checks for `FEATURE_SETTINGS` for view and edit access.

### `Settings_taak.php` (Task Settings Controller)

*   **Purpose:** Manages the global, default configurations for tasks that can be used within project templates. This allows for standardization of common tasks across different projects.
*   **Key Methods:**
    *   `index()`: Displays the main view for listing and managing these default task settings.
    *   `table()`: Provides data for a server-side datatable that lists the configured task settings from `pc_instellingen_taak`.
    *   `edit($id = '')`: Handles the creation or editing of a default task configuration.
        *   **POST:** Saves the task setting's properties, including its name, a unique tag name (which is used to find/create a Perfex tag ID), the default staff member to assign it to, and an optional view URL.
        *   **GET:** Prepares the form for adding or editing a task setting, loading staff members for selection.
    *   `delete($id = '')`: Deletes a default task setting. Crucially, it also removes this task setting from any project templates that might be using it (`project_template_tasks_model->delete_by_settings_taak_id()`) and then re-adjusts the `task_order` of remaining settings.
    *   `task_order_up($id)` / `task_order_down($id)`: Allows reordering of the default task settings.
*   **Models Used:** `Staff_model`, `Settings_taak_model`, `Project_template_tasks_model`.
*   **Permissions:** Checks for `FEATURE_SETTINGS` for view, create, edit, and delete operations.

### `Taak.php` (Single Task View/Comment Controller)

*   **Purpose:** This controller appears to be designed for viewing details of a specific Perfex task and managing a general comment associated with that task through the Venntech module. It's not for editing the core task details themselves but rather for supplementary information or actions.
*   **Key Methods:**
    *   `index()`: Likely displays a view related to a single task or its comments.
    *   `edit($id = '')`: The `$id` parameter refers to a Perfex Task ID.
        *   **POST:** Adds or updates a comment in the `tbltask_comments` table (Perfex's standard task comments table). The `contact_id` for the comment is hardcoded to 0. Allows the task to be marked as complete (status 5) from this interface.
        *   **GET:** Prepares data to display a task's details and its associated Venntech comment (if any). It fetches the main task data, any existing comment from `task_comments_model` (potentially a Venntech-specific comment table or a specific type of comment from the main table), and the assigned staff member for the task.
*   **Models Used:** `Task_comments_model` (likely for Perfex task comments), `Taken_model` (Venntech's task model, used here to get staff ID), `Tasks_model` (Perfex's main task model).
*   **Permissions:** Checks for `FEATURE_TAKEN` for view, create, and edit operations.

### `Taken.php` (Tasks List/Redirection Controller)

*   **Purpose:** This controller serves as the main access point for viewing tasks relevant to the Venntech module. It lists tasks and, when an "edit" action is initiated, intelligently redirects the user to the appropriate specialized interface (e.g., inspection report, delivery document) if the task is linked to one. It also handles changing task assignments.
*   **Key Methods:**
    *   `index()`: Displays the primary list view of tasks managed or relevant to the Venntech module.
    *   `table()`: Provides data for the server-side datatable that lists these tasks.
    *   `edit($id = '')`: This method acts as a dispatcher. Based on the task ID (`$id`), it checks if the task is linked to a Venntech inspection report, delivery document, or installation date.
        *   If a link exists, it redirects the user to the edit page of that specific Venntech entity.
        *   Otherwise, it redirects to `venntech/taak/edit/{task_id}` for general task commenting.
    *   `delete($id = '')`: Deletes a task using `taken_model->delete($id)`. The exact scope of this deletion (Perfex task vs. Venntech links) depends on the `taken_model`.
    *   `change_staffid($task_id)`: An AJAX endpoint that updates the staff member assigned to a specific Perfex task. It directly modifies the `tbltask_assigned` table and returns updated HTML for the task's timer controls.
*   **Models Used:** `Taken_model`, `Tasks_model` (Perfex's main task model).
*   **Permissions:** Checks for `FEATURE_TAKEN` for view and delete operations.
