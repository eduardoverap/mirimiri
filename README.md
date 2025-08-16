# Mirimiri

Mirimiri is an MVC application based on JavaScript and PHP. It allows users to extract kanjis from a text, view these on a table, and import data from the KANJIDIC XML database (not included here).

## Tech Stack

- **Backend:** PHP (MVC architecture)
- **Database:** SQLite
- **Web Server:** Apache
- **Dependencies:** Composer, jQuery, [DataTables](https://datatables.net/)
- **Frontend:** Vanilla JavaScript (fetch API)

## Installation

### Requirements

- PHP 8.0+  
- Apache server (or a compatible setup like XAMPP, MAMP, etc.)  
- Composer

### Setup Steps

1. **Clone the repository**

   ```bash
   git clone https://github.com/eduardoverap/mirimiri.git

2. **Install dependencies**

   ```bash
   composer install

3. **Deploy**

   - Option 1: Copy the project folder into the `/htdocs/` directory of your XAMPP/MAMP setup.
   - Option 2: Mount the folder in a PHP + Apache container.

4. **Launch the application**

   Open your browser and navigate to http://localhost/mirimiri

5. **Get the XML databases**

   To get the kanji information, you need to import the XML databases from the EDRDG Documentation Wiki. Download them from the [KANJIDIC Project](https://www.edrdg.org/wiki/KANJIDIC_Project.html) and [JMdict-EDICT Dictionary Project](https://www.edrdg.org/wiki/JMdict-EDICT_Dictionary_Project.html) pages. Uncompress the GZ files and put the XMLs in the `/database/` folder. Then go to the `Import center` in the application and proceed with the import.

## Project Status

This is an early alpha version. Some core functionality is implemented, but additional improvements and features are planned.

## Author

Developed by Eduardo Vera Palomino.  
LinkedIn: [@eduardoverap](https://www.linkedin.com/in/eduardoverap/) | GitHub: [@eduardoverap](https://github.com/eduardoverap/)

## License

This project is licensed under the MIT License.