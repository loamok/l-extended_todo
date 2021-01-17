import { baseCallback, postOne, putOne, getOne } from '../commons/functions';

const debug = false;


export function postOneWtParameter(values, successCallback, errorCallback) {
    postOne(values, successCallback, errorCallback, Routing.generate('api_wt_parameters_post_collection'));
}

export function putOneWtParameter(id, values, successCallback, errorCallback) {
    putOne(values, successCallback, errorCallback, Routing.generate('api_wt_parameters_put_item', {'id': id}));
}

export function getOneWtParameter(id, successCallback, errorCallback) {
    getOne(successCallback, errorCallback, Routing.generate('api_wt_parameters_get_item', {'id': id}));
}
