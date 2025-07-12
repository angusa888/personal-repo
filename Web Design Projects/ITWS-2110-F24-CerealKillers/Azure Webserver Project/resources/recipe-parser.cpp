#include <iostream>
#include <fstream>
#include <queue>
#include <sstream>
#include <algorithm>
#include <list>

int main() {
    std::ifstream recipes_file("recipes-to-clean.json");
    if (!recipes_file.is_open()) {
        std::cerr << "Failed to open file." << std::endl;
        exit(1);
    }

    std::string clean_json = "[\n";
    
    std::string line;
    while (std::getline(recipes_file, line)) {     
        std::string name = "", url = "", ingredients = "";

        std::string name_temp = line.substr(line.find("\"name\""));
        name_temp = name_temp.substr(0, name_temp.find("\", ") + 3);

        std::string url_temp = line.substr(line.find("\"url\""));
        url_temp = url_temp.substr(0, url_temp.find("\", ") + 3);

        std::string ingredients_temp = line.substr(line.find("\"ingredients\""));
        ingredients_temp = ingredients_temp.substr(0, ingredients_temp.find("\", ") + 1);

        clean_json += "\t{\n\t\t" + name_temp + "\n\t\t" + url_temp + "\n\t\t" + ingredients_temp + "\n" + "\t},\n";
    }
    recipes_file.close();

    clean_json[clean_json.length() - 2] = ' ';
    clean_json += "]";

    std::ofstream output_file("c-recipes.json");
    output_file << clean_json;
}