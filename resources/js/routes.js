import {route} from "ziggy-js";

const direction = route()?.t?.routes
export const uris = Object.keys(direction).map((key)=>{
    if(direction[key].methods.includes('GET')){
        return `${import.meta.env.VITE_APP_URL}/${direction[key].uri ? direction[key].uri === '/' ? '' : direction[key].uri : ''}`;
    }
}).filter(route => route !== undefined)
