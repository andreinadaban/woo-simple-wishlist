/**
 * Simple Wishlist for WooCommerce
 * Copyright (C) 2018-2019 Andrei Nadaban
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

'use strict';

import {add} from './add';
import {remove} from './remove';
import {clear} from './clear';

/**
 * Selects the target button.
 */
export function getButton(e) {

    let $ = jQuery;
    let button = false;

    if ( $(e.target).hasClass('sw-button-ajax') ) {
        button = $(e.target);
    }

    if ( $(e.target).parents('.sw-button-ajax').length > 0 ) {
        button = $(e.target).parents('.sw-button-ajax');
    }

    return button;

}

/**
 * Gets the button parameter and decides what function to run when the button is clicked.
 */
export function getAction(button) {

    let output = {};

    if ( button.hasClass('sw-button-add') ) {
        output.parameter = 'sw-add';
        output.fn = add;
    }

    if ( button.hasClass('sw-button-remove') ) {
        output.parameter = 'sw-remove';
        output.fn = remove;
    }

    if ( button.hasClass('sw-button-clear') ) {
        output.parameter = 'sw-clear';
        output.fn = clear;
    }

    return output;

}

/**
 * Gets URL parameters.
 */
export function getURLParameters(url) {

    // Get query string from url (optional) or window.
    let queryString = url ? url.split('?')[1] : window.location.search.slice(1);

    // We'll store the parameters here.
    let obj = {};

    // If query string exists.
    if ( queryString ) {

        // Stuff after # is not part of query string, so get rid of it.
        queryString = queryString.split('#')[0];

        // Split our query string into its component parts.
        let arr = queryString.split('&');

        for (let i = 0; i < arr.length; i++) {
            // Separate the keys and the values.
            let a = arr[i].split('=');

            // Set parameter name and value (use 'true' if empty).
            let paramName = a[0];
            let paramValue = typeof (a[1]) === 'undefined' ? true : a[1];

            // Keep case consistent (optional).
            paramName = paramName.toLowerCase();
            if ( typeof paramValue === 'string' ) paramValue = paramValue.toLowerCase();

            // If the paramName ends with square brackets, e.g. colors[] or colors[2].
            if ( paramName.match(/\[(\d+)?\]$/) ) {

                // Create key if it doesn't exist.
                let key = paramName.replace(/\[(\d+)?\]/, '');
                if ( !obj[key] ) obj[key] = [];

                // If it's an indexed array e.g. colors[2].
                if ( paramName.match(/\[\d+\]$/) ) {
                    // Get the index value and add the entry at the appropriate position.
                    let index = /\[(\d+)\]/.exec(paramName)[1];
                    obj[key][index] = paramValue;
                } else {
                    // Otherwise add the value to the end of the array.
                    obj[key].push(paramValue);
                }
            } else {
                // We're dealing with a string.
                if ( !obj[paramName] ) {
                    // If it doesn't exist, create property.
                    obj[paramName] = paramValue;
                } else if ( obj[paramName] && typeof obj[paramName] === 'string' ) {
                    // If property does exist and it's a string, convert it to an array.
                    obj[paramName] = [obj[paramName]];
                    obj[paramName].push(paramValue);
                } else {
                    // Otherwise add the property.
                    obj[paramName].push(paramValue);
                }
            }
        }
    }

    return obj;

}
