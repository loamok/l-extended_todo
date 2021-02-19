import { baseCallback, postOne, putOne, getOne } from '../commons/functions';

const debug = false;

export function postPrepareQuery(values, successCallback, errorCallback) {
    console.log('values :', values);
    postOne(values, successCallback, errorCallback, Routing.generate('async_wtt_actions_prepare'));
}
