import { baseCallback, postOne, putOne, getOne } from '../commons/functions';

const debug = false;


export function postOneDayParameters(values, successCallback, errorCallback) {
    postOne(values, successCallback, errorCallback, Routing.generate('api_day_parameters_post_collection'));
}

export function putOneDayParameters(id, values, successCallback, errorCallback) {
    putOne(values, successCallback, errorCallback, Routing.generate('api_day_parameters_put_item', {'id': id}));
}

export function getOneDayParameters(id, successCallback, errorCallback) {
    getOne(successCallback, errorCallback, Routing.generate('api_day_parameters_get_item', {'id': id}));
}
