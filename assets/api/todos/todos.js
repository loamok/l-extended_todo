import { baseCallback, postOne, putOne, getOne } from '../commons/functions';

const debug = false;


export function postOneTodo(values, successCallback, errorCallback) {
    postOne(values, successCallback, errorCallback, Routing.generate('api_todos_post_collection'));
}

export function putOneTodo(id, values, successCallback, errorCallback) {
    putOne(values, successCallback, errorCallback, Routing.generate('api_todos_put_item', {'id': id}));
}

export function getOneTodos(id, successCallback, errorCallback) {
    getOne(successCallback, errorCallback, Routing.generate('api_todos_get_item', {'id': id}));
}
