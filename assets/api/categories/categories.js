import { baseCallback, postOne, putOne, getOne, getCollection } from '../commons/functions';

const debug = false;

export function getOneCategory(id, successCallback, errorCallback) {
    getOne(successCallback, errorCallback, Routing.generate('api_categories_get_item', {'id': id}));
}

export function getAllCategories(success, error) {
    getCollection(success, error, Routing.generate('api_categories_get_collection'));
}
