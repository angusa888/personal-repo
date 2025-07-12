// select a random recipe from recipes-final.json in the resources folder

import fs from 'fs';
import path from 'path';

const getRandomRecipe = () => {
    const filePath = path.join(__dirname, 'resources', 'recipes-final.json');
    const data = fs.readFileSync(filePath, 'utf8');
    const recipes = JSON.parse(data);
    const randomIndex = Math.floor(Math.random() * recipes.length);
    return recipes[randomIndex];
};

const randomRecipe = getRandomRecipe();