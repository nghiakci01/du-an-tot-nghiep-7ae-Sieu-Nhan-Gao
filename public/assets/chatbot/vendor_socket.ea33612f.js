/*! *****************************************************************************
Copyright (c) Microsoft Corporation.

Permission to use, copy, modify, and/or distribute this software for any
purpose with or without fee is hereby granted.

THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH
REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY
AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT,
INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM
LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR
OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR
PERFORMANCE OF THIS SOFTWARE.
***************************************************************************** */
var e=function(t,n){return(e=Object.setPrototypeOf||{__proto__:[]}instanceof Array&&function(e,t){e.__proto__=t}||function(e,t){for(var n in t)Object.prototype.hasOwnProperty.call(t,n)&&(e[n]=t[n])})(t,n)};var t=function(){return(t=Object.assign||function(e){for(var t,n=1,r=arguments.length;n<r;n++)for(var i in t=arguments[n])Object.prototype.hasOwnProperty.call(t,i)&&(e[i]=t[i]);return e}).apply(this,arguments)};function n(e,t,n,r){return new(n||(n=Promise))((function(i,o){function s(e){try{c(r.next(e))}catch(t){o(t)}}function a(e){try{c(r.throw(e))}catch(t){o(t)}}function c(e){var t;e.done?i(e.value):(t=e.value,t instanceof n?t:new n((function(e){e(t)}))).then(s,a)}c((r=r.apply(e,t||[])).next())}))}function r(e,t){var n,r,i,o,s={label:0,sent:function(){if(1&i[0])throw i[1];return i[1]},trys:[],ops:[]};return o={next:a(0),throw:a(1),return:a(2)},"function"==typeof Symbol&&(o[Symbol.iterator]=function(){return this}),o;function a(o){return function(a){return function(o){if(n)throw new TypeError("Generator is already executing.");for(;s;)try{if(n=1,r&&(i=2&o[0]?r.return:o[0]?r.throw||((i=r.return)&&i.call(r),0):r.next)&&!(i=i.call(r,o[1])).done)return i;switch(r=0,i&&(o=[2&o[0],i.value]),o[0]){case 0:case 1:i=o;break;case 4:return s.label++,{value:o[1],done:!1};case 5:s.label++,r=o[1],o=[0];continue;case 7:o=s.ops.pop(),s.trys.pop();continue;default:if(!(i=s.trys,(i=i.length>0&&i[i.length-1])||6!==o[0]&&2!==o[0])){s=0;continue}if(3===o[0]&&(!i||o[1]>i[0]&&o[1]<i[3])){s.label=o[1];break}if(6===o[0]&&s.label<i[1]){s.label=i[1],i=o;break}if(i&&s.label<i[2]){s.label=i[2],s.ops.push(o);break}i[2]&&s.ops.pop(),s.trys.pop();continue}o=t.call(e,s)}catch(a){o=[6,a],r=0}finally{n=i=0}if(5&o[0])throw o[1];return{value:o[0]?o[1]:void 0,done:!0}}([o,a])}}}function i(e){var t="function"==typeof Symbol&&Symbol.iterator,n=t&&e[t],r=0;if(n)return n.call(e);if(e&&"number"==typeof e.length)return{next:function(){return e&&r>=e.length&&(e=void 0),{value:e&&e[r++],done:!e}}};throw new TypeError(t?"Object is not iterable.":"Symbol.iterator is not defined.")}function o(e,t){var n="function"==typeof Symbol&&e[Symbol.iterator];if(!n)return e;var r,i,o=n.call(e),s=[];try{for(;(void 0===t||t-- >0)&&!(r=o.next()).done;)s.push(r.value)}catch(a){i={error:a}}finally{try{r&&!r.done&&(n=o.return)&&n.call(o)}finally{if(i)throw i.error}}return s}function s(){for(var e=0,t=0,n=arguments.length;t<n;t++)e+=arguments[t].length;var r=Array(e),i=0;for(t=0;t<n;t++)for(var o=arguments[t],s=0,a=o.length;s<a;s++,i++)r[i]=o[s];return r}function a(e,t,n){if(n||2===arguments.length)for(var r,i=0,o=t.length;i<o;i++)!r&&i in t||(r||(r=Array.prototype.slice.call(t,0,i)),r[i]=t[i]);return e.concat(r||Array.prototype.slice.call(t))}
/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function c(e,t){if(!(t instanceof Object))return t;switch(t.constructor){case Date:return new Date(t.getTime());case Object:void 0===e&&(e={});break;case Array:e=[];break;default:return t}for(var n in t)t.hasOwnProperty(n)&&"__proto__"!==n&&(e[n]=c(e[n],t[n]));return e}
/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
var u=function(){function e(){var e=this;this.reject=function(){},this.resolve=function(){},this.promise=new Promise((function(t,n){e.resolve=t,e.reject=n}))}return e.prototype.wrapCallback=function(e){var t=this;return function(n,r){n?t.reject(n):t.resolve(r),"function"==typeof e&&(t.promise.catch((function(){})),1===e.length?e(n):e(n,r))}},e}();function l(){try{return"[object process]"===Object.prototype.toString.call(global.process)}catch(e){return!1}}
/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
var f=function(t){function n(e,r,i){var o=t.call(this,r)||this;return o.code=e,o.customData=i,o.name="FirebaseError",Object.setPrototypeOf(o,n.prototype),Error.captureStackTrace&&Error.captureStackTrace(o,p.prototype.create),o}return function(t,n){if("function"!=typeof n&&null!==n)throw new TypeError("Class extends value "+String(n)+" is not a constructor or null");function r(){this.constructor=t}e(t,n),t.prototype=null===n?Object.create(n):(r.prototype=n.prototype,new r)}(n,t),n}(Error),p=function(){function e(e,t,n){this.service=e,this.serviceName=t,this.errors=n}return e.prototype.create=function(e){for(var t=[],n=1;n<arguments.length;n++)t[n-1]=arguments[n];var r=t[0]||{},i=this.service+"/"+e,o=this.errors[e],s=o?d(o,r):"Error",a=this.serviceName+": "+s+" ("+i+").",c=new f(i,a,r);return c},e}();function d(e,t){return e.replace(h,(function(e,n){var r=t[n];return null!=r?String(r):"<"+n+"?>"}))}var h=/\{\$([^}]+)}/g;
/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function v(e,t){return Object.prototype.hasOwnProperty.call(e,t)}function g(e,t){var n=new b(e,t);return n.subscribe.bind(n)}var b=function(){function e(e,t){var n=this;this.observers=[],this.unsubscribes=[],this.observerCount=0,this.task=Promise.resolve(),this.finalized=!1,this.onNoObservers=t,this.task.then((function(){e(n)})).catch((function(e){n.error(e)}))}return e.prototype.next=function(e){this.forEachObserver((function(t){t.next(e)}))},e.prototype.error=function(e){this.forEachObserver((function(t){t.error(e)})),this.close(e)},e.prototype.complete=function(){this.forEachObserver((function(e){e.complete()})),this.close()},e.prototype.subscribe=function(e,t,n){var r,i=this;if(void 0===e&&void 0===t&&void 0===n)throw new Error("Missing Observer.");void 0===(r=function(e,t){if("object"!=typeof e||null===e)return!1;for(var n=0,r=t;n<r.length;n++){var i=r[n];if(i in e&&"function"==typeof e[i])return!0}return!1}(e,["next","error","complete"])?e:{next:e,error:t,complete:n}).next&&(r.next=y),void 0===r.error&&(r.error=y),void 0===r.complete&&(r.complete=y);var o=this.unsubscribeOne.bind(this,this.observers.length);return this.finalized&&this.task.then((function(){try{i.finalError?r.error(i.finalError):r.complete()}catch(e){}})),this.observers.push(r),o},e.prototype.unsubscribeOne=function(e){void 0!==this.observers&&void 0!==this.observers[e]&&(delete this.observers[e],this.observerCount-=1,0===this.observerCount&&void 0!==this.onNoObservers&&this.onNoObservers(this))},e.prototype.forEachObserver=function(e){if(!this.finalized)for(var t=0;t<this.observers.length;t++)this.sendOne(t,e)},e.prototype.sendOne=function(e,t){var n=this;this.task.then((function(){if(void 0!==n.observers&&void 0!==n.observers[e])try{t(n.observers[e])}catch(r){"undefined"!=typeof console&&console.error&&console.error(r)}}))},e.prototype.close=function(e){var t=this;this.finalized||(this.finalized=!0,void 0!==e&&(this.finalError=e),this.task.then((function(){t.observers=void 0,t.onNoObservers=void 0})))},e}();function y(){}var m=function(){function e(e,t,n){this.name=e,this.instanceFactory=t,this.type=n,this.multipleInstances=!1,this.serviceProps={},this.instantiationMode="LAZY",this.onInstanceCreated=null}return e.prototype.setInstantiationMode=function(e){return this.instantiationMode=e,this},e.prototype.setMultipleInstances=function(e){return this.multipleInstances=e,this},e.prototype.setServiceProps=function(e){return this.serviceProps=e,this},e.prototype.setInstanceCreatedCallback=function(e){return this.onInstanceCreated=e,this},e}(),w=function(){function e(e,t){this.name=e,this.container=t,this.component=null,this.instances=new Map,this.instancesDeferred=new Map,this.instancesOptions=new Map,this.onInitCallbacks=new Map}return e.prototype.get=function(e){var t=this.normalizeInstanceIdentifier(e);if(!this.instancesDeferred.has(t)){var n=new u;if(this.instancesDeferred.set(t,n),this.isInitialized(t)||this.shouldAutoInitialize())try{var r=this.getOrInitializeService({instanceIdentifier:t});r&&n.resolve(r)}catch(i){}}return this.instancesDeferred.get(t).promise},e.prototype.getImmediate=function(e){var t,n=this.normalizeInstanceIdentifier(null==e?void 0:e.identifier),r=null!==(t=null==e?void 0:e.optional)&&void 0!==t&&t;if(!this.isInitialized(n)&&!this.shouldAutoInitialize()){if(r)return null;throw Error("Service "+this.name+" is not available")}try{return this.getOrInitializeService({instanceIdentifier:n})}catch(i){if(r)return null;throw i}},e.prototype.getComponent=function(){return this.component},e.prototype.setComponent=function(e){var t,n;if(e.name!==this.name)throw Error("Mismatching Component "+e.name+" for Provider "+this.name+".");if(this.component)throw Error("Component for "+this.name+" has already been provided");if(this.component=e,this.shouldAutoInitialize()){if(function(e){return"EAGER"===e.instantiationMode}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */(e))try{this.getOrInitializeService({instanceIdentifier:"[DEFAULT]"})}catch(p){}try{for(var r=i(this.instancesDeferred.entries()),s=r.next();!s.done;s=r.next()){var a=o(s.value,2),c=a[0],u=a[1],l=this.normalizeInstanceIdentifier(c);try{var f=this.getOrInitializeService({instanceIdentifier:l});u.resolve(f)}catch(p){}}}catch(d){t={error:d}}finally{try{s&&!s.done&&(n=r.return)&&n.call(r)}finally{if(t)throw t.error}}}},e.prototype.clearInstance=function(e){void 0===e&&(e="[DEFAULT]"),this.instancesDeferred.delete(e),this.instancesOptions.delete(e),this.instances.delete(e)},e.prototype.delete=function(){return n(this,void 0,void 0,(function(){var e;return r(this,(function(t){switch(t.label){case 0:return e=Array.from(this.instances.values()),[4,Promise.all(a(a([],o(e.filter((function(e){return"INTERNAL"in e})).map((function(e){return e.INTERNAL.delete()})))),o(e.filter((function(e){return"_delete"in e})).map((function(e){return e._delete()})))))];case 1:return t.sent(),[2]}}))}))},e.prototype.isComponentSet=function(){return null!=this.component},e.prototype.isInitialized=function(e){return void 0===e&&(e="[DEFAULT]"),this.instances.has(e)},e.prototype.getOptions=function(e){return void 0===e&&(e="[DEFAULT]"),this.instancesOptions.get(e)||{}},e.prototype.initialize=function(e){var t,n;void 0===e&&(e={});var r=e.options,s=void 0===r?{}:r,a=this.normalizeInstanceIdentifier(e.instanceIdentifier);if(this.isInitialized(a))throw Error(this.name+"("+a+") has already been initialized");if(!this.isComponentSet())throw Error("Component "+this.name+" has not been registered yet");var c=this.getOrInitializeService({instanceIdentifier:a,options:s});try{for(var u=i(this.instancesDeferred.entries()),l=u.next();!l.done;l=u.next()){var f=o(l.value,2),p=f[0],d=f[1];a===this.normalizeInstanceIdentifier(p)&&d.resolve(c)}}catch(h){t={error:h}}finally{try{l&&!l.done&&(n=u.return)&&n.call(u)}finally{if(t)throw t.error}}return c},e.prototype.onInit=function(e,t){var n,r=this.normalizeInstanceIdentifier(t),i=null!==(n=this.onInitCallbacks.get(r))&&void 0!==n?n:new Set;i.add(e),this.onInitCallbacks.set(r,i);var o=this.instances.get(r);return o&&e(o,r),function(){i.delete(e)}},e.prototype.invokeOnInitCallbacks=function(e,t){var n,r,o=this.onInitCallbacks.get(t);if(o)try{for(var s=i(o),a=s.next();!a.done;a=s.next()){var c=a.value;try{c(e,t)}catch(u){}}}catch(l){n={error:l}}finally{try{a&&!a.done&&(r=s.return)&&r.call(s)}finally{if(n)throw n.error}}},e.prototype.getOrInitializeService=function(e){var t,n=e.instanceIdentifier,r=e.options,i=void 0===r?{}:r,o=this.instances.get(n);if(!o&&this.component&&(o=this.component.instanceFactory(this.container,{instanceIdentifier:(t=n,"[DEFAULT]"===t?void 0:t),options:i}),this.instances.set(n,o),this.instancesOptions.set(n,i),this.invokeOnInitCallbacks(o,n),this.component.onInstanceCreated))try{this.component.onInstanceCreated(this.container,n,o)}catch(s){}return o||null},e.prototype.normalizeInstanceIdentifier=function(e){return void 0===e&&(e="[DEFAULT]"),this.component?this.component.multipleInstances?e:"[DEFAULT]":e},e.prototype.shouldAutoInitialize=function(){return!!this.component&&"EXPLICIT"!==this.component.instantiationMode},e}();
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */var I,k=function(){function e(e){this.name=e,this.providers=new Map}return e.prototype.addComponent=function(e){var t=this.getProvider(e.name);if(t.isComponentSet())throw new Error("Component "+e.name+" has already been registered with "+this.name);t.setComponent(e)},e.prototype.addOrOverwriteComponent=function(e){this.getProvider(e.name).isComponentSet()&&this.providers.delete(e.name),this.addComponent(e)},e.prototype.getProvider=function(e){if(this.providers.has(e))return this.providers.get(e);var t=new w(e,this);return this.providers.set(e,t),t},e.prototype.getProviders=function(){return Array.from(this.providers.values())},e}();
/*! *****************************************************************************
Copyright (c) Microsoft Corporation. All rights reserved.
Licensed under the Apache License, Version 2.0 (the "License"); you may not use
this file except in compliance with the License. You may obtain a copy of the
License at http://www.apache.org/licenses/LICENSE-2.0

THIS CODE IS PROVIDED ON AN *AS IS* BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
KIND, EITHER EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION ANY IMPLIED
WARRANTIES OR CONDITIONS OF TITLE, FITNESS FOR A PARTICULAR PURPOSE,
MERCHANTABLITY OR NON-INFRINGEMENT.

See the Apache Version 2.0 License for specific language governing permissions
and limitations under the License.
***************************************************************************** */function S(){for(var e=0,t=0,n=arguments.length;t<n;t++)e+=arguments[t].length;var r=Array(e),i=0;for(t=0;t<n;t++)for(var o=arguments[t],s=0,a=o.length;s<a;s++,i++)r[i]=o[s];return r}
/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */var _,E,C=[];(E=_||(_={}))[E.DEBUG=0]="DEBUG",E[E.VERBOSE=1]="VERBOSE",E[E.INFO=2]="INFO",E[E.WARN=3]="WARN",E[E.ERROR=4]="ERROR",E[E.SILENT=5]="SILENT";var O,T={debug:_.DEBUG,verbose:_.VERBOSE,info:_.INFO,warn:_.WARN,error:_.ERROR,silent:_.SILENT},D=_.INFO,P=((I={})[_.DEBUG]="log",I[_.VERBOSE]="log",I[_.INFO]="info",I[_.WARN]="warn",I[_.ERROR]="error",I),N=function(e,t){for(var n=[],r=2;r<arguments.length;r++)n[r-2]=arguments[r];if(!(t<e.logLevel)){var i=(new Date).toISOString(),o=P[t];if(!o)throw new Error("Attempted to log a message with an invalid logType (value: "+t+")");console[o].apply(console,S(["["+i+"]  "+e.name+":"],n))}},A=function(){function e(e){this.name=e,this._logLevel=D,this._logHandler=N,this._userLogHandler=null,C.push(this)}return Object.defineProperty(e.prototype,"logLevel",{get:function(){return this._logLevel},set:function(e){if(!(e in _))throw new TypeError('Invalid value "'+e+'" assigned to `logLevel`');this._logLevel=e},enumerable:!1,configurable:!0}),e.prototype.setLogLevel=function(e){this._logLevel="string"==typeof e?T[e]:e},Object.defineProperty(e.prototype,"logHandler",{get:function(){return this._logHandler},set:function(e){if("function"!=typeof e)throw new TypeError("Value assigned to `logHandler` must be a function");this._logHandler=e},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"userLogHandler",{get:function(){return this._userLogHandler},set:function(e){this._userLogHandler=e},enumerable:!1,configurable:!0}),e.prototype.debug=function(){for(var e=[],t=0;t<arguments.length;t++)e[t]=arguments[t];this._userLogHandler&&this._userLogHandler.apply(this,S([this,_.DEBUG],e)),this._logHandler.apply(this,S([this,_.DEBUG],e))},e.prototype.log=function(){for(var e=[],t=0;t<arguments.length;t++)e[t]=arguments[t];this._userLogHandler&&this._userLogHandler.apply(this,S([this,_.VERBOSE],e)),this._logHandler.apply(this,S([this,_.VERBOSE],e))},e.prototype.info=function(){for(var e=[],t=0;t<arguments.length;t++)e[t]=arguments[t];this._userLogHandler&&this._userLogHandler.apply(this,S([this,_.INFO],e)),this._logHandler.apply(this,S([this,_.INFO],e))},e.prototype.warn=function(){for(var e=[],t=0;t<arguments.length;t++)e[t]=arguments[t];this._userLogHandler&&this._userLogHandler.apply(this,S([this,_.WARN],e)),this._logHandler.apply(this,S([this,_.WARN],e))},e.prototype.error=function(){for(var e=[],t=0;t<arguments.length;t++)e[t]=arguments[t];this._userLogHandler&&this._userLogHandler.apply(this,S([this,_.ERROR],e)),this._logHandler.apply(this,S([this,_.ERROR],e))},e}();function j(e){C.forEach((function(t){t.setLogLevel(e)}))}var L,M=((O={})["no-app"]="No Firebase App '{$appName}' has been created - call Firebase App.initializeApp()",O["bad-app-name"]="Illegal App name: '{$appName}",O["duplicate-app"]="Firebase App named '{$appName}' already exists",O["app-deleted"]="Firebase App named '{$appName}' already deleted",O["invalid-app-argument"]="firebase.{$appName}() takes either no argument or a Firebase App instance.",O["invalid-log-argument"]="First argument to `onLog` must be null or a function.",O),R=new p("app","Firebase",M),x=((L={})["@firebase/app"]="fire-core",L["@firebase/analytics"]="fire-analytics",L["@firebase/app-check"]="fire-app-check",L["@firebase/auth"]="fire-auth",L["@firebase/database"]="fire-rtdb",L["@firebase/functions"]="fire-fn",L["@firebase/installations"]="fire-iid",L["@firebase/messaging"]="fire-fcm",L["@firebase/performance"]="fire-perf",L["@firebase/remote-config"]="fire-rc",L["@firebase/storage"]="fire-gcs",L["@firebase/firestore"]="fire-fst",L["fire-js"]="fire-js",L["firebase-wrapper"]="fire-js-all",L),K=new A("@firebase/app"),F=function(){function e(e,t,n){var r=this;this.firebase_=n,this.isDeleted_=!1,this.name_=t.name,this.automaticDataCollectionEnabled_=t.automaticDataCollectionEnabled||!1,this.options_=c(void 0,e),this.container=new k(t.name),this._addComponent(new m("app",(function(){return r}),"PUBLIC")),this.firebase_.INTERNAL.components.forEach((function(e){return r._addComponent(e)}))}return Object.defineProperty(e.prototype,"automaticDataCollectionEnabled",{get:function(){return this.checkDestroyed_(),this.automaticDataCollectionEnabled_},set:function(e){this.checkDestroyed_(),this.automaticDataCollectionEnabled_=e},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"name",{get:function(){return this.checkDestroyed_(),this.name_},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"options",{get:function(){return this.checkDestroyed_(),this.options_},enumerable:!1,configurable:!0}),e.prototype.delete=function(){var e=this;return new Promise((function(t){e.checkDestroyed_(),t()})).then((function(){return e.firebase_.INTERNAL.removeApp(e.name_),Promise.all(e.container.getProviders().map((function(e){return e.delete()})))})).then((function(){e.isDeleted_=!0}))},e.prototype._getService=function(e,t){var n;void 0===t&&(t="[DEFAULT]"),this.checkDestroyed_();var r=this.container.getProvider(e);return r.isInitialized()||"EXPLICIT"!==(null===(n=r.getComponent())||void 0===n?void 0:n.instantiationMode)||r.initialize(),r.getImmediate({identifier:t})},e.prototype._removeServiceInstance=function(e,t){void 0===t&&(t="[DEFAULT]"),this.container.getProvider(e).clearInstance(t)},e.prototype._addComponent=function(e){try{this.container.addComponent(e)}catch(t){K.debug("Component "+e.name+" failed to register with FirebaseApp "+this.name,t)}},e.prototype._addOrOverwriteComponent=function(e){this.container.addOrOverwriteComponent(e)},e.prototype.toJSON=function(){return{name:this.name,automaticDataCollectionEnabled:this.automaticDataCollectionEnabled,options:this.options}},e.prototype.checkDestroyed_=function(){if(this.isDeleted_)throw R.create("app-deleted",{appName:this.name_})},e}();F.prototype.name&&F.prototype.options||F.prototype.delete||console.log("dc");
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
function B(e){var t={},n=new Map,r={__esModule:!0,initializeApp:function(n,i){void 0===i&&(i={});if("object"!=typeof i||null===i){i={name:i}}var o=i;void 0===o.name&&(o.name="[DEFAULT]");var s=o.name;if("string"!=typeof s||!s)throw R.create("bad-app-name",{appName:String(s)});if(v(t,s))throw R.create("duplicate-app",{appName:s});var a=new e(n,o,r);return t[s]=a,a},app:i,registerVersion:function(e,t,n){var r,i=null!==(r=x[e])&&void 0!==r?r:e;n&&(i+="-"+n);var s=i.match(/\s|\//),a=t.match(/\s|\//);if(s||a){var c=['Unable to register library "'+i+'" with version "'+t+'":'];return s&&c.push('library name "'+i+'" contains illegal characters (whitespace or "/")'),s&&a&&c.push("and"),a&&c.push('version name "'+t+'" contains illegal characters (whitespace or "/")'),void K.warn(c.join(" "))}o(new m(i+"-version",(function(){return{library:i,version:t}}),"VERSION"))},setLogLevel:j,onLog:function(e,t){if(null!==e&&"function"!=typeof e)throw R.create("invalid-log-argument");!function(e,t){for(var n=function(n){var r=null;t&&t.level&&(r=T[t.level]),n.userLogHandler=null===e?null:function(t,n){for(var i=[],o=2;o<arguments.length;o++)i[o-2]=arguments[o];var s=i.map((function(e){if(null==e)return null;if("string"==typeof e)return e;if("number"==typeof e||"boolean"==typeof e)return e.toString();if(e instanceof Error)return e.message;try{return JSON.stringify(e)}catch(t){return null}})).filter((function(e){return e})).join(" ");n>=(null!=r?r:t.logLevel)&&e({level:_[n].toLowerCase(),message:s,args:i,type:t.name})}},r=0,i=C;r<i.length;r++)n(i[r])}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */(e,t)},apps:null,SDK_VERSION:"8.10.0",INTERNAL:{registerComponent:o,removeApp:function(e){delete t[e]},components:n,useAsService:function(e,t){if("serverAuth"===t)return null;return t}}};function i(e){if(!v(t,e=e||"[DEFAULT]"))throw R.create("no-app",{appName:e});return t[e]}function o(o){var s=o.name;if(n.has(s))return K.debug("There were multiple attempts to register component "+s+"."),"PUBLIC"===o.type?r[s]:null;if(n.set(s,o),"PUBLIC"===o.type){var a=function(e){if(void 0===e&&(e=i()),"function"!=typeof e[s])throw R.create("invalid-app-argument",{appName:s});return e[s]()};void 0!==o.serviceProps&&c(a,o.serviceProps),r[s]=a,e.prototype[s]=function(){for(var e=[],t=0;t<arguments.length;t++)e[t]=arguments[t];var n=this._getService.bind(this,s);return n.apply(this,o.multipleInstances?e:[])}}for(var u=0,l=Object.keys(t);u<l.length;u++){var f=l[u];t[f]._addComponent(o)}return"PUBLIC"===o.type?r[s]:null}return r.default=r,Object.defineProperty(r,"apps",{get:function(){return Object.keys(t).map((function(e){return t[e]}))}}),i.App=e,r}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */var H=function e(){var n=B(F);return n.INTERNAL=t(t({},n.INTERNAL),{createFirebaseNamespace:e,extendNamespace:function(e){c(n,e)},createSubscribe:g,ErrorFactory:p,deepExtend:c}),n}(),U=function(){function e(e){this.container=e}return e.prototype.getPlatformInfoString=function(){return this.container.getProviders().map((function(e){if(function(e){var t=e.getComponent();return"VERSION"===(null==t?void 0:t.type)}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */(e)){var t=e.getImmediate();return t.library+"/"+t.version}return null})).filter((function(e){return e})).join(" ")},e}();
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
if("object"==typeof self&&self.self===self&&void 0!==self.firebase){K.warn("\n    Warning: Firebase is already defined in the global scope. Please make sure\n    Firebase library is only loaded once.\n  ");var V=self.firebase.SDK_VERSION;V&&V.indexOf("LITE")>=0&&K.warn("\n    Warning: You are trying to load Firebase while using Firebase Performance standalone script.\n    You should load Firebase Performance with this instance of Firebase to avoid loading duplicate code.\n    ")}var z=H.initializeApp;H.initializeApp=function(){for(var e=[],t=0;t<arguments.length;t++)e[t]=arguments[t];return l()&&K.warn('\n      Warning: This is a browser-targeted Firebase bundle but it appears it is being\n      run in a Node environment.  If running in a Node environment, make sure you\n      are using the bundle specified by the "main" field in package.json.\n      \n      If you are using Webpack, you can specify "main" as the first item in\n      "resolve.mainFields":\n      https://webpack.js.org/configuration/resolve/#resolvemainfields\n      \n      If using Rollup, use the @rollup/plugin-node-resolve plugin and specify "main"\n      as the first item in "mainFields", e.g. [\'main\', \'module\'].\n      https://github.com/rollup/@rollup/plugin-node-resolve\n      '),z.apply(void 0,e)};var q,W,$=H;function G(e){return Array.prototype.slice.call(e)}function J(e){return new Promise((function(t,n){e.onsuccess=function(){t(e.result)},e.onerror=function(){n(e.error)}}))}function Y(e,t,n){var r,i=new Promise((function(i,o){J(r=e[t].apply(e,n)).then(i,o)}));return i.request=r,i}function X(e,t,n){var r=Y(e,t,n);return r.then((function(e){if(e)return new re(e,r.request)}))}function Z(e,t,n){n.forEach((function(n){Object.defineProperty(e.prototype,n,{get:function(){return this[t][n]},set:function(e){this[t][n]=e}})}))}function Q(e,t,n,r){r.forEach((function(r){r in n.prototype&&(e.prototype[r]=function(){return Y(this[t],r,arguments)})}))}function ee(e,t,n,r){r.forEach((function(r){r in n.prototype&&(e.prototype[r]=function(){return this[t][r].apply(this[t],arguments)})}))}function te(e,t,n,r){r.forEach((function(r){r in n.prototype&&(e.prototype[r]=function(){return X(this[t],r,arguments)})}))}function ne(e){this._index=e}function re(e,t){this._cursor=e,this._request=t}function ie(e){this._store=e}function oe(e){this._tx=e,this.complete=new Promise((function(t,n){e.oncomplete=function(){t()},e.onerror=function(){n(e.error)},e.onabort=function(){n(e.error)}}))}function se(e,t,n){this._db=e,this.oldVersion=t,this.transaction=new oe(n)}function ae(e){this._db=e}function ce(e,t,n){var r=Y(indexedDB,"open",[e,t]),i=r.request;return i&&(i.onupgradeneeded=function(e){n&&n(new se(i.result,e.oldVersion,i.transaction))}),r.then((function(e){return new ae(e)}))}function ue(e){return Y(indexedDB,"deleteDatabase",[e])}(q=$).INTERNAL.registerComponent(new m("platform-logger",(function(e){return new U(e)}),"PRIVATE")),q.registerVersion("@firebase/app","0.6.30",W),q.registerVersion("fire-js",""),Z(ne,"_index",["name","keyPath","multiEntry","unique"]),Q(ne,"_index",IDBIndex,["get","getKey","getAll","getAllKeys","count"]),te(ne,"_index",IDBIndex,["openCursor","openKeyCursor"]),Z(re,"_cursor",["direction","key","primaryKey","value"]),Q(re,"_cursor",IDBCursor,["update","delete"]),["advance","continue","continuePrimaryKey"].forEach((function(e){e in IDBCursor.prototype&&(re.prototype[e]=function(){var t=this,n=arguments;return Promise.resolve().then((function(){return t._cursor[e].apply(t._cursor,n),J(t._request).then((function(e){if(e)return new re(e,t._request)}))}))})})),ie.prototype.createIndex=function(){return new ne(this._store.createIndex.apply(this._store,arguments))},ie.prototype.index=function(){return new ne(this._store.index.apply(this._store,arguments))},Z(ie,"_store",["name","keyPath","indexNames","autoIncrement"]),Q(ie,"_store",IDBObjectStore,["put","add","delete","clear","get","getAll","getKey","getAllKeys","count"]),te(ie,"_store",IDBObjectStore,["openCursor","openKeyCursor"]),ee(ie,"_store",IDBObjectStore,["deleteIndex"]),oe.prototype.objectStore=function(){return new ie(this._tx.objectStore.apply(this._tx,arguments))},Z(oe,"_tx",["objectStoreNames","mode"]),ee(oe,"_tx",IDBTransaction,["abort"]),se.prototype.createObjectStore=function(){return new ie(this._db.createObjectStore.apply(this._db,arguments))},Z(se,"_db",["name","version","objectStoreNames"]),ee(se,"_db",IDBDatabase,["deleteObjectStore","close"]),ae.prototype.transaction=function(){return new oe(this._db.transaction.apply(this._db,arguments))},Z(ae,"_db",["name","version","objectStoreNames"]),ee(ae,"_db",IDBDatabase,["close"]),["openCursor","openKeyCursor"].forEach((function(e){[ie,ne].forEach((function(t){e in t.prototype&&(t.prototype[e.replace("open","iterate")]=function(){var t=G(arguments),n=t[t.length-1],r=this._store||this._index,i=r[e].apply(r,t.slice(0,-1));i.onsuccess=function(){n(i.result)}})}))})),[ne,ie].forEach((function(e){e.prototype.getAll||(e.prototype.getAll=function(e,t){var n=this,r=[];return new Promise((function(i){n.iterateCursor(e,(function(e){e?(r.push(e.value),void 0===t||r.length!=t?e.continue():i(r)):i(r)}))}))})}));var le,fe=((le={})["missing-app-config-values"]='Missing App configuration value: "{$valueName}"',le["not-registered"]="Firebase Installation is not registered.",le["installation-not-found"]="Firebase Installation not found.",le["request-failed"]='{$requestName} request failed with error "{$serverCode} {$serverStatus}: {$serverMessage}"',le["app-offline"]="Could not process request. Application offline.",le["delete-pending-registration"]="Can't delete installation while there is a pending registration request.",le),pe=new p("installations","Installations",fe);function de(e){return e instanceof f&&e.code.includes("request-failed")}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function he(e){return"https://firebaseinstallations.googleapis.com/v1/projects/"+e.projectId+"/installations"}function ve(e){return{token:e.token,requestStatus:2,expiresIn:(t=e.expiresIn,Number(t.replace("s","000"))),creationTime:Date.now()};var t}function ge(e,t){return n(this,void 0,void 0,(function(){var n,i;return r(this,(function(r){switch(r.label){case 0:return[4,t.json()];case 1:return n=r.sent(),i=n.error,[2,pe.create("request-failed",{requestName:e,serverCode:i.code,serverMessage:i.message,serverStatus:i.status})]}}))}))}function be(e){var t=e.apiKey;return new Headers({"Content-Type":"application/json",Accept:"application/json","x-goog-api-key":t})}function ye(e,t){var n=t.refreshToken,r=be(e);return r.append("Authorization",function(e){return"FIS_v2 "+e}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */(n)),r}function me(e){return n(this,void 0,void 0,(function(){var t;return r(this,(function(n){switch(n.label){case 0:return[4,e()];case 1:return(t=n.sent()).status>=500&&t.status<600?[2,e()]:[2,t]}}))}))}function we(e,t){var i=t.fid;return n(this,void 0,void 0,(function(){var t,n,o,s,a,c;return r(this,(function(r){switch(r.label){case 0:return t=he(e),n=be(e),o={fid:i,authVersion:"FIS_v2",appId:e.appId,sdkVersion:"w:0.4.32"},s={method:"POST",headers:n,body:JSON.stringify(o)},[4,me((function(){return fetch(t,s)}))];case 1:return(a=r.sent()).ok?[4,a.json()]:[3,3];case 2:return c=r.sent(),[2,{fid:c.fid||i,registrationStatus:2,refreshToken:c.refreshToken,authToken:ve(c.authToken)}];case 3:return[4,ge("Create Installation",a)];case 4:throw r.sent()}}))}))}
/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Ie(e){return new Promise((function(t){setTimeout(t,e)}))}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
var ke=/^[cdef][\w-]{21}$/;function Se(){try{var e=new Uint8Array(17);(self.crypto||self.msCrypto).getRandomValues(e),e[0]=112+e[0]%16;var t=function(e){return(t=e,btoa(String.fromCharCode.apply(String,a([],o(t)))).replace(/\+/g,"-").replace(/\//g,"_")).substr(0,22);var t}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */(e);return ke.test(t)?t:""}catch(n){return""}}function _e(e){return e.appName+"!"+e.appId}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */var Ee=new Map;function Ce(e,t){var n=_e(e);Oe(n,t),function(e,t){var n=De();n&&n.postMessage({key:e,fid:t});Pe()}(n,t)}function Oe(e,t){var n,r,o=Ee.get(e);if(o)try{for(var s=i(o),a=s.next();!a.done;a=s.next()){(0,a.value)(t)}}catch(c){n={error:c}}finally{try{a&&!a.done&&(r=s.return)&&r.call(s)}finally{if(n)throw n.error}}}var Te=null;function De(){return!Te&&"BroadcastChannel"in self&&((Te=new BroadcastChannel("[Firebase] FID Change")).onmessage=function(e){Oe(e.data.key,e.data.fid)}),Te}function Pe(){0===Ee.size&&Te&&(Te.close(),Te=null)}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */var Ne,Ae,je="firebase-installations-store",Le=null;function Me(){return Le||(Le=ce("firebase-installations-database",1,(function(e){switch(e.oldVersion){case 0:e.createObjectStore(je)}}))),Le}function Re(e,t){return n(this,void 0,void 0,(function(){var n,i,o,s,a;return r(this,(function(r){switch(r.label){case 0:return n=_e(e),[4,Me()];case 1:return i=r.sent(),o=i.transaction(je,"readwrite"),[4,(s=o.objectStore(je)).get(n)];case 2:return a=r.sent(),[4,s.put(t,n)];case 3:return r.sent(),[4,o.complete];case 4:return r.sent(),a&&a.fid===t.fid||Ce(e,t.fid),[2,t]}}))}))}function xe(e){return n(this,void 0,void 0,(function(){var t,n,i;return r(this,(function(r){switch(r.label){case 0:return t=_e(e),[4,Me()];case 1:return n=r.sent(),[4,(i=n.transaction(je,"readwrite")).objectStore(je).delete(t)];case 2:return r.sent(),[4,i.complete];case 3:return r.sent(),[2]}}))}))}function Ke(e,t){return n(this,void 0,void 0,(function(){var n,i,o,s,a,c;return r(this,(function(r){switch(r.label){case 0:return n=_e(e),[4,Me()];case 1:return i=r.sent(),o=i.transaction(je,"readwrite"),[4,(s=o.objectStore(je)).get(n)];case 2:return a=r.sent(),void 0!==(c=t(a))?[3,4]:[4,s.delete(n)];case 3:return r.sent(),[3,6];case 4:return[4,s.put(c,n)];case 5:r.sent(),r.label=6;case 6:return[4,o.complete];case 7:return r.sent(),!c||a&&a.fid===c.fid||Ce(e,c.fid),[2,c]}}))}))}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Fe(e){return n(this,void 0,void 0,(function(){var t,i,o;return r(this,(function(s){switch(s.label){case 0:return[4,Ke(e,(function(i){var o=function(e){return Ue(e||{fid:Se(),registrationStatus:0})}(i),s=function(e,t){if(0===t.registrationStatus){if(!navigator.onLine)return{installationEntry:t,registrationPromise:Promise.reject(pe.create("app-offline"))};var i={fid:t.fid,registrationStatus:1,registrationTime:Date.now()};return{installationEntry:i,registrationPromise:function(e,t){return n(this,void 0,void 0,(function(){var n,i;return r(this,(function(r){switch(r.label){case 0:return r.trys.push([0,2,,7]),[4,we(e,t)];case 1:return n=r.sent(),[2,Re(e,n)];case 2:return de(i=r.sent())&&409===i.customData.serverCode?[4,xe(e)]:[3,4];case 3:return r.sent(),[3,6];case 4:return[4,Re(e,{fid:t.fid,registrationStatus:0})];case 5:r.sent(),r.label=6;case 6:throw i;case 7:return[2]}}))}))}(e,i)}}return 1===t.registrationStatus?{installationEntry:t,registrationPromise:Be(e)}:{installationEntry:t}}(e,o);return t=s.registrationPromise,s.installationEntry}))];case 1:return""!==(i=s.sent()).fid?[3,3]:(o={},[4,t]);case 2:return[2,(o.installationEntry=s.sent(),o)];case 3:return[2,{installationEntry:i,registrationPromise:t}]}}))}))}function Be(e){return n(this,void 0,void 0,(function(){var t,n,i,o;return r(this,(function(r){switch(r.label){case 0:return[4,He(e)];case 1:t=r.sent(),r.label=2;case 2:return 1!==t.registrationStatus?[3,5]:[4,Ie(100)];case 3:return r.sent(),[4,He(e)];case 4:return t=r.sent(),[3,2];case 5:return 0!==t.registrationStatus?[3,7]:[4,Fe(e)];case 6:return n=r.sent(),i=n.installationEntry,(o=n.registrationPromise)?[2,o]:[2,i];case 7:return[2,t]}}))}))}function He(e){return Ke(e,(function(e){if(!e)throw pe.create("installation-not-found");return Ue(e)}))}function Ue(e){return 1===(t=e).registrationStatus&&t.registrationTime+1e4<Date.now()?{fid:e.fid,registrationStatus:0}:e;var t;
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */}function Ve(e,t){var i=e.appConfig,o=e.platformLoggerProvider;return n(this,void 0,void 0,(function(){var e,n,s,a,c,u,l;return r(this,(function(r){switch(r.label){case 0:return e=function(e,t){var n=t.fid;return he(e)+"/"+n+"/authTokens:generate"}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */(i,t),n=ye(i,t),(s=o.getImmediate({optional:!0}))&&n.append("x-firebase-client",s.getPlatformInfoString()),a={installation:{sdkVersion:"w:0.4.32"}},c={method:"POST",headers:n,body:JSON.stringify(a)},[4,me((function(){return fetch(e,c)}))];case 1:return(u=r.sent()).ok?[4,u.json()]:[3,3];case 2:return l=r.sent(),[2,ve(l)];case 3:return[4,ge("Generate Auth Token",u)];case 4:throw r.sent()}}))}))}function ze(e,i){return void 0===i&&(i=!1),n(this,void 0,void 0,(function(){var o,s,a;return r(this,(function(c){switch(c.label){case 0:return[4,Ke(e.appConfig,(function(s){if(!We(s))throw pe.create("not-registered");var a=s.authToken;if(!i&&function(e){return 2===e.requestStatus&&!function(e){var t=Date.now();return t<e.creationTime||e.creationTime+e.expiresIn<t+36e5}(e)}(a))return s;if(1===a.requestStatus)return o=function(e,t){return n(this,void 0,void 0,(function(){var n,i;return r(this,(function(r){switch(r.label){case 0:return[4,qe(e.appConfig)];case 1:n=r.sent(),r.label=2;case 2:return 1!==n.authToken.requestStatus?[3,5]:[4,Ie(100)];case 3:return r.sent(),[4,qe(e.appConfig)];case 4:return n=r.sent(),[3,2];case 5:return 0===(i=n.authToken).requestStatus?[2,ze(e,t)]:[2,i]}}))}))}(e,i),s;if(!navigator.onLine)throw pe.create("app-offline");var c=function(e){var n={requestStatus:1,requestTime:Date.now()};return t(t({},e),{authToken:n})}(s);return o=function(e,i){return n(this,void 0,void 0,(function(){var n,o,s;return r(this,(function(r){switch(r.label){case 0:return r.trys.push([0,3,,8]),[4,Ve(e,i)];case 1:return n=r.sent(),s=t(t({},i),{authToken:n}),[4,Re(e.appConfig,s)];case 2:return r.sent(),[2,n];case 3:return!de(o=r.sent())||401!==o.customData.serverCode&&404!==o.customData.serverCode?[3,5]:[4,xe(e.appConfig)];case 4:return r.sent(),[3,7];case 5:return s=t(t({},i),{authToken:{requestStatus:0}}),[4,Re(e.appConfig,s)];case 6:r.sent(),r.label=7;case 7:throw o;case 8:return[2]}}))}))}(e,c),c}))];case 1:return s=c.sent(),o?[4,o]:[3,3];case 2:return a=c.sent(),[3,4];case 3:a=s.authToken,c.label=4;case 4:return[2,a]}}))}))}function qe(e){return Ke(e,(function(e){if(!We(e))throw pe.create("not-registered");var n,r=e.authToken;return 1===(n=r).requestStatus&&n.requestTime+1e4<Date.now()?t(t({},e),{authToken:{requestStatus:0}}):e}))}function We(e){return void 0!==e&&2===e.registrationStatus}function $e(e){return n(this,void 0,void 0,(function(){var t;return r(this,(function(n){switch(n.label){case 0:return[4,Fe(e)];case 1:return(t=n.sent().registrationPromise)?[4,t]:[3,3];case 2:n.sent(),n.label=3;case 3:return[2]}}))}))}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Ge(e,t){return n(this,void 0,void 0,(function(){var n,i,o,s;return r(this,(function(r){switch(r.label){case 0:return n=function(e,t){var n=t.fid;return he(e)+"/"+n}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */(e,t),i=ye(e,t),o={method:"DELETE",headers:i},[4,me((function(){return fetch(n,o)}))];case 1:return(s=r.sent()).ok?[3,3]:[4,ge("Delete Installation",s)];case 2:throw r.sent();case 3:return[2]}}))}))}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
function Je(e,t){var n=e.appConfig;return function(e,t){De();var n=_e(e),r=Ee.get(n);r||(r=new Set,Ee.set(n,r)),r.add(t)}(n,t),function(){!function(e,t){var n=_e(e),r=Ee.get(n);r&&(r.delete(t),0===r.size&&Ee.delete(n),Pe())}(n,t)}}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Ye(e){return pe.create("missing-app-config-values",{valueName:e})}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */(Ne=$).INTERNAL.registerComponent(new m("installations",(function(e){var t=e.getProvider("app").getImmediate(),o={appConfig:function(e){var t,n;if(!e||!e.options)throw Ye("App Configuration");if(!e.name)throw Ye("App Name");try{for(var r=i(["projectId","apiKey","appId"]),o=r.next();!o.done;o=r.next()){var s=o.value;if(!e.options[s])throw Ye(s)}}catch(a){t={error:a}}finally{try{o&&!o.done&&(n=r.return)&&n.call(r)}finally{if(t)throw t.error}}return{appName:e.name,projectId:e.options.projectId,apiKey:e.options.apiKey,appId:e.options.appId}}(t),platformLoggerProvider:e.getProvider("platform-logger")};return{app:t,getId:function(){
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
return function(e){return n(this,void 0,void 0,(function(){var t,n,i;return r(this,(function(r){switch(r.label){case 0:return[4,Fe(e.appConfig)];case 1:return t=r.sent(),n=t.installationEntry,(i=t.registrationPromise)?i.catch(console.error):ze(e).catch(console.error),[2,n.fid]}}))}))}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */(o)},getToken:function(e){return function(e,t){return void 0===t&&(t=!1),n(this,void 0,void 0,(function(){return r(this,(function(n){switch(n.label){case 0:return[4,$e(e.appConfig)];case 1:return n.sent(),[4,ze(e,t)];case 2:return[2,n.sent().token]}}))}))}(o,e)},delete:function(){return function(e){return n(this,void 0,void 0,(function(){var t,n;return r(this,(function(r){switch(r.label){case 0:return[4,Ke(t=e.appConfig,(function(e){if(!e||0!==e.registrationStatus)return e}))];case 1:if(!(n=r.sent()))return[3,6];if(1!==n.registrationStatus)return[3,2];throw pe.create("delete-pending-registration");case 2:if(2!==n.registrationStatus)return[3,6];if(navigator.onLine)return[3,3];throw pe.create("app-offline");case 3:return[4,Ge(t,n)];case 4:return r.sent(),[4,xe(t)];case 5:r.sent(),r.label=6;case 6:return[2]}}))}))}(o)},onIdChange:function(e){return Je(o,e)}}}),"PUBLIC")),Ne.registerVersion("@firebase/installations","0.4.32");var Xe,Ze,Qe=((Ae={})["missing-app-config-values"]='Missing App configuration value: "{$valueName}"',Ae["only-available-in-window"]="This method is available in a Window context.",Ae["only-available-in-sw"]="This method is available in a service worker context.",Ae["permission-default"]="The notification permission was not granted and dismissed instead.",Ae["permission-blocked"]="The notification permission was not granted and blocked instead.",Ae["unsupported-browser"]="This browser doesn't support the API's required to use the firebase SDK.",Ae["failed-service-worker-registration"]="We are unable to register the default service worker. {$browserErrorMessage}",Ae["token-subscribe-failed"]="A problem occurred while subscribing the user to FCM: {$errorInfo}",Ae["token-subscribe-no-token"]="FCM returned no token when subscribing the user to push.",Ae["token-unsubscribe-failed"]="A problem occurred while unsubscribing the user from FCM: {$errorInfo}",Ae["token-update-failed"]="A problem occurred while updating the user from FCM: {$errorInfo}",Ae["token-update-no-token"]="FCM returned no token when updating the user to push.",Ae["use-sw-after-get-token"]="The useServiceWorker() method may only be called once and must be called before calling getToken() to ensure your service worker is used.",Ae["invalid-sw-registration"]="The input to useServiceWorker() must be a ServiceWorkerRegistration.",Ae["invalid-bg-handler"]="The input to setBackgroundMessageHandler() must be a function.",Ae["invalid-vapid-key"]="The public VAPID key must be a string.",Ae["use-vapid-key-after-get-token"]="The usePublicVapidKey() method may only be called once and must be called before calling getToken() to ensure your VAPID key is used.",Ae),et=new p("messaging","Messaging",Qe),tt="BDOU99-h67HcA6JeFXHbSNMu7e2yNNu3RzoMj8TM4W88jITfq7ZmPvIM1Iv-4_l2LxQcYwhqby2xGpWwzjfAnG4";
/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
function nt(e){var t=new Uint8Array(e);return btoa(String.fromCharCode.apply(String,a([],o(t)))).replace(/=/g,"").replace(/\+/g,"-").replace(/\//g,"_")}function rt(e){for(var t=(e+"=".repeat((4-e.length%4)%4)).replace(/\-/g,"+").replace(/_/g,"/"),n=atob(t),r=new Uint8Array(n.length),i=0;i<n.length;++i)r[i]=n.charCodeAt(i);return r}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */(Ze=Xe||(Xe={})).PUSH_RECEIVED="push-received",Ze.NOTIFICATION_CLICKED="notification-clicked";function it(e){return n(this,void 0,void 0,(function(){var t,i,o=this;return r(this,(function(s){switch(s.label){case 0:return"databases"in indexedDB?[4,indexedDB.databases()]:[3,2];case 1:if(t=s.sent(),!t.map((function(e){return e.name})).includes("fcm_token_details_db"))return[2,null];s.label=2;case 2:return i=null,[4,ce("fcm_token_details_db",5,(function(t){return n(o,void 0,void 0,(function(){var n,o,s,a;return r(this,(function(r){switch(r.label){case 0:return t.oldVersion<2?[2]:t.objectStoreNames.contains("fcm_token_object_Store")?[4,(n=t.transaction.objectStore("fcm_token_object_Store")).index("fcmSenderId").get(e)]:[2];case 1:return o=r.sent(),[4,n.clear()];case 2:if(r.sent(),!o)return[2];if(2===t.oldVersion){if(!(s=o).auth||!s.p256dh||!s.endpoint)return[2];i={token:s.fcmToken,createTime:null!==(a=s.createTime)&&void 0!==a?a:Date.now(),subscriptionOptions:{auth:s.auth,p256dh:s.p256dh,endpoint:s.endpoint,swScope:s.swScope,vapidKey:"string"==typeof s.vapidKey?s.vapidKey:nt(s.vapidKey)}}}else(3===t.oldVersion||4===t.oldVersion)&&(i={token:(s=o).fcmToken,createTime:s.createTime,subscriptionOptions:{auth:nt(s.auth),p256dh:nt(s.p256dh),endpoint:s.endpoint,swScope:s.swScope,vapidKey:nt(s.vapidKey)}});return[2]}}))}))}))];case 3:return s.sent().close(),[4,ue("fcm_token_details_db")];case 4:return s.sent(),[4,ue("fcm_vapid_details_db")];case 5:return s.sent(),[4,ue("undefined")];case 6:return s.sent(),[2,ot(i)?i:null]}}))}))}function ot(e){if(!e||!e.subscriptionOptions)return!1;var t=e.subscriptionOptions;return"number"==typeof e.createTime&&e.createTime>0&&"string"==typeof e.token&&e.token.length>0&&"string"==typeof t.auth&&t.auth.length>0&&"string"==typeof t.p256dh&&t.p256dh.length>0&&"string"==typeof t.endpoint&&t.endpoint.length>0&&"string"==typeof t.swScope&&t.swScope.length>0&&"string"==typeof t.vapidKey&&t.vapidKey.length>0}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */var st="firebase-messaging-store",at=null;function ct(){return at||(at=ce("firebase-messaging-database",1,(function(e){switch(e.oldVersion){case 0:e.createObjectStore(st)}}))),at}function ut(e){return n(this,void 0,void 0,(function(){var t,n,i;return r(this,(function(r){switch(r.label){case 0:return t=pt(e),[4,ct()];case 1:return[4,r.sent().transaction(st).objectStore(st).get(t)];case 2:return(n=r.sent())?[2,n]:[3,3];case 3:return[4,it(e.appConfig.senderId)];case 4:return(i=r.sent())?[4,lt(e,i)]:[3,6];case 5:return r.sent(),[2,i];case 6:return[2]}}))}))}function lt(e,t){return n(this,void 0,void 0,(function(){var n,i,o;return r(this,(function(r){switch(r.label){case 0:return n=pt(e),[4,ct()];case 1:return i=r.sent(),[4,(o=i.transaction(st,"readwrite")).objectStore(st).put(t,n)];case 2:return r.sent(),[4,o.complete];case 3:return r.sent(),[2,t]}}))}))}function ft(e){return n(this,void 0,void 0,(function(){var t,n,i;return r(this,(function(r){switch(r.label){case 0:return t=pt(e),[4,ct()];case 1:return n=r.sent(),[4,(i=n.transaction(st,"readwrite")).objectStore(st).delete(t)];case 2:return r.sent(),[4,i.complete];case 3:return r.sent(),[2]}}))}))}function pt(e){return e.appConfig.appId}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function dt(e,t){return n(this,void 0,void 0,(function(){var n,i,o,s,a,c;return r(this,(function(r){switch(r.label){case 0:return[4,bt(e)];case 1:n=r.sent(),i=yt(t),o={method:"POST",headers:n,body:JSON.stringify(i)},r.label=2;case 2:return r.trys.push([2,5,,6]),[4,fetch(gt(e.appConfig),o)];case 3:return[4,r.sent().json()];case 4:return s=r.sent(),[3,6];case 5:throw a=r.sent(),et.create("token-subscribe-failed",{errorInfo:a});case 6:if(s.error)throw c=s.error.message,et.create("token-subscribe-failed",{errorInfo:c});if(!s.token)throw et.create("token-subscribe-no-token");return[2,s.token]}}))}))}function ht(e,t){return n(this,void 0,void 0,(function(){var n,i,o,s,a,c;return r(this,(function(r){switch(r.label){case 0:return[4,bt(e)];case 1:n=r.sent(),i=yt(t.subscriptionOptions),o={method:"PATCH",headers:n,body:JSON.stringify(i)},r.label=2;case 2:return r.trys.push([2,5,,6]),[4,fetch(gt(e.appConfig)+"/"+t.token,o)];case 3:return[4,r.sent().json()];case 4:return s=r.sent(),[3,6];case 5:throw a=r.sent(),et.create("token-update-failed",{errorInfo:a});case 6:if(s.error)throw c=s.error.message,et.create("token-update-failed",{errorInfo:c});if(!s.token)throw et.create("token-update-no-token");return[2,s.token]}}))}))}function vt(e,t){return n(this,void 0,void 0,(function(){var n,i,o,s,a;return r(this,(function(r){switch(r.label){case 0:return[4,bt(e)];case 1:n=r.sent(),i={method:"DELETE",headers:n},r.label=2;case 2:return r.trys.push([2,5,,6]),[4,fetch(gt(e.appConfig)+"/"+t,i)];case 3:return[4,r.sent().json()];case 4:if((o=r.sent()).error)throw s=o.error.message,et.create("token-unsubscribe-failed",{errorInfo:s});return[3,6];case 5:throw a=r.sent(),et.create("token-unsubscribe-failed",{errorInfo:a});case 6:return[2]}}))}))}function gt(e){return"https://fcmregistrations.googleapis.com/v1/projects/"+e.projectId+"/registrations"}function bt(e){var t=e.appConfig,i=e.installations;return n(this,void 0,void 0,(function(){var e;return r(this,(function(n){switch(n.label){case 0:return[4,i.getToken()];case 1:return e=n.sent(),[2,new Headers({"Content-Type":"application/json",Accept:"application/json","x-goog-api-key":t.apiKey,"x-goog-firebase-installations-auth":"FIS "+e})]}}))}))}function yt(e){var t=e.p256dh,n=e.auth,r=e.endpoint,i=e.vapidKey,o={web:{endpoint:r,auth:n,p256dh:t}};return i!==tt&&(o.web.applicationPubKey=i),o}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function mt(e,t,i){return n(this,void 0,void 0,(function(){var n,o,s,a;return r(this,(function(r){switch(r.label){case 0:if("granted"!==Notification.permission)throw et.create("permission-blocked");return[4,St(t,i)];case 1:return n=r.sent(),[4,ut(e)];case 2:return o=r.sent(),s={vapidKey:i,swScope:t.scope,endpoint:n.endpoint,auth:nt(n.getKey("auth")),p256dh:nt(n.getKey("p256dh"))},o?[3,3]:[2,kt(e,s)];case 3:if(c=o.subscriptionOptions,l=(u=s).vapidKey===c.vapidKey,f=u.endpoint===c.endpoint,p=u.auth===c.auth,d=u.p256dh===c.p256dh,l&&f&&p&&d)return[3,8];r.label=4;case 4:return r.trys.push([4,6,,7]),[4,vt(e,o.token)];case 5:return r.sent(),[3,7];case 6:return a=r.sent(),console.warn(a),[3,7];case 7:return[2,kt(e,s)];case 8:return Date.now()>=o.createTime+6048e5?[2,It({token:o.token,createTime:Date.now(),subscriptionOptions:s},e,t)]:[2,o.token];case 9:return[2]}var c,u,l,f,p,d;
/**
 * @license
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */}))}))}function wt(e,t){return n(this,void 0,void 0,(function(){var n,i;return r(this,(function(r){switch(r.label){case 0:return[4,ut(e)];case 1:return(n=r.sent())?[4,vt(e,n.token)]:[3,4];case 2:return r.sent(),[4,ft(e)];case 3:r.sent(),r.label=4;case 4:return[4,t.pushManager.getSubscription()];case 5:return(i=r.sent())?[2,i.unsubscribe()]:[2,!0]}}))}))}function It(e,i,o){return n(this,void 0,void 0,(function(){var n,s,a;return r(this,(function(r){switch(r.label){case 0:return r.trys.push([0,3,,5]),[4,ht(i,e)];case 1:return n=r.sent(),s=t(t({},e),{token:n,createTime:Date.now()}),[4,lt(i,s)];case 2:return r.sent(),[2,n];case 3:return a=r.sent(),[4,wt(i,o)];case 4:throw r.sent(),a;case 5:return[2]}}))}))}function kt(e,t){return n(this,void 0,void 0,(function(){var n,i;return r(this,(function(r){switch(r.label){case 0:return[4,dt(e,t)];case 1:return n=r.sent(),i={token:n,createTime:Date.now(),subscriptionOptions:t},[4,lt(e,i)];case 2:return r.sent(),[2,i.token]}}))}))}function St(e,t){return n(this,void 0,void 0,(function(){var n;return r(this,(function(r){switch(r.label){case 0:return[4,e.pushManager.getSubscription()];case 1:return(n=r.sent())?[2,n]:[2,e.pushManager.subscribe({userVisibleOnly:!0,applicationServerKey:rt(t)})]}}))}))}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
function _t(e){return"object"==typeof e&&!!e&&"google.c.a.c_id"in e}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */function Et(e){return new Promise((function(t){setTimeout(t,e)}))}
/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */var Ct=function(){function e(e){var t=this;this.firebaseDependencies=e,this.isOnBackgroundMessageUsed=null,this.vapidKey=null,this.bgMessageHandler=null,self.addEventListener("push",(function(e){e.waitUntil(t.onPush(e))})),self.addEventListener("pushsubscriptionchange",(function(e){e.waitUntil(t.onSubChange(e))})),self.addEventListener("notificationclick",(function(e){e.waitUntil(t.onNotificationClick(e))}))}return Object.defineProperty(e.prototype,"app",{get:function(){return this.firebaseDependencies.app},enumerable:!1,configurable:!0}),e.prototype.setBackgroundMessageHandler=function(e){if(this.isOnBackgroundMessageUsed=!1,!e||"function"!=typeof e)throw et.create("invalid-bg-handler");this.bgMessageHandler=e},e.prototype.onBackgroundMessage=function(e){var t=this;return this.isOnBackgroundMessageUsed=!0,this.bgMessageHandler=e,function(){t.bgMessageHandler=null}},e.prototype.getToken=function(){var e,t;return n(this,void 0,void 0,(function(){var n;return r(this,(function(r){switch(r.label){case 0:return this.vapidKey?[3,2]:[4,ut(this.firebaseDependencies)];case 1:n=r.sent(),this.vapidKey=null!==(t=null===(e=null==n?void 0:n.subscriptionOptions)||void 0===e?void 0:e.vapidKey)&&void 0!==t?t:tt,r.label=2;case 2:return[2,mt(this.firebaseDependencies,self.registration,this.vapidKey)]}}))}))},e.prototype.deleteToken=function(){return wt(this.firebaseDependencies,self.registration)},e.prototype.requestPermission=function(){throw et.create("only-available-in-window")},e.prototype.usePublicVapidKey=function(e){if(null!==this.vapidKey)throw et.create("use-vapid-key-after-get-token");if("string"!=typeof e||0===e.length)throw et.create("invalid-vapid-key");this.vapidKey=e},e.prototype.useServiceWorker=function(){throw et.create("only-available-in-window")},e.prototype.onMessage=function(){throw et.create("only-available-in-window")},e.prototype.onTokenRefresh=function(){throw et.create("only-available-in-window")},e.prototype.onPush=function(e){return n(this,void 0,void 0,(function(){var t,n,i,o;return r(this,(function(r){switch(r.label){case 0:return(t=function(e){var t=e.data;if(!t)return null;try{return t.json()}catch(n){return null}}(e))?[4,Pt()]:(console.debug("FirebaseMessaging: failed to get parsed MessagePayload from the PushEvent. Skip handling the push."),[2]);case 1:return function(e){return e.some((function(e){return"visible"===e.visibilityState&&!e.url.startsWith("chrome-extension://")}))}(n=r.sent())?[2,Dt(n,t)]:(i=!1,t.notification?[4,Nt(Ot(t))]:[3,3]);case 2:r.sent(),i=!0,r.label=3;case 3:return!0===i&&!1===this.isOnBackgroundMessageUsed?[2]:(this.bgMessageHandler&&(o=function(e){var t={from:e.from,collapseKey:e.collapse_key,messageId:e.fcm_message_id};return function(e,t){if(t.notification){e.notification={};var n=t.notification.title;n&&(e.notification.title=n);var r=t.notification.body;r&&(e.notification.body=r);var i=t.notification.image;i&&(e.notification.image=i)}}(t,e),function(e,t){t.data&&(e.data=t.data)}(t,e),function(e,t){if(t.fcmOptions){e.fcmOptions={};var n=t.fcmOptions.link;n&&(e.fcmOptions.link=n);var r=t.fcmOptions.analytics_label;r&&(e.fcmOptions.analyticsLabel=r)}}(t,e),t}(t),"function"==typeof this.bgMessageHandler?this.bgMessageHandler(o):this.bgMessageHandler.next(o)),[4,Et(1e3)]);case 4:return r.sent(),[2]}}))}))},e.prototype.onSubChange=function(e){var t,i;return n(this,void 0,void 0,(function(){var n;return r(this,(function(r){switch(r.label){case 0:return e.newSubscription?[3,2]:[4,wt(this.firebaseDependencies,self.registration)];case 1:return r.sent(),[2];case 2:return[4,ut(this.firebaseDependencies)];case 3:return n=r.sent(),[4,wt(this.firebaseDependencies,self.registration)];case 4:return r.sent(),[4,mt(this.firebaseDependencies,self.registration,null!==(i=null===(t=null==n?void 0:n.subscriptionOptions)||void 0===t?void 0:t.vapidKey)&&void 0!==i?i:tt)];case 5:return r.sent(),[2]}}))}))},e.prototype.onNotificationClick=function(e){var t,i;return n(this,void 0,void 0,(function(){var n,o,s,a,c;return r(this,(function(r){switch(r.label){case 0:return(n=null===(i=null===(t=e.notification)||void 0===t?void 0:t.data)||void 0===i?void 0:i.FCM_MSG)?e.action?[2]:(e.stopImmediatePropagation(),e.notification.close(),(o=function(e){var t,n,r,i=null!==(n=null===(t=e.fcmOptions)||void 0===t?void 0:t.link)&&void 0!==n?n:null===(r=e.notification)||void 0===r?void 0:r.click_action;if(i)return i;return _t(e.data)?self.location.origin:null}
/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */(n))?(s=new URL(o,self.location.href),a=new URL(self.location.origin),s.host!==a.host?[2]:[4,Tt(s)]):[2]):[2];case 1:return(c=r.sent())?[3,4]:[4,self.clients.openWindow(o)];case 2:return c=r.sent(),[4,Et(3e3)];case 3:return r.sent(),[3,6];case 4:return[4,c.focus()];case 5:c=r.sent(),r.label=6;case 6:return c?(n.messageType=Xe.NOTIFICATION_CLICKED,n.isFirebaseMessaging=!0,[2,c.postMessage(n)]):[2]}}))}))},e}();function Ot(e){var n,r=t({},e.notification);return r.data=((n={}).FCM_MSG=e,n),r}function Tt(e){return n(this,void 0,void 0,(function(){var t,n,o,s,a,c,u;return r(this,(function(r){switch(r.label){case 0:return[4,Pt()];case 1:t=r.sent();try{for(n=i(t),o=n.next();!o.done;o=n.next())if(s=o.value,a=new URL(s.url,self.location.href),e.host===a.host)return[2,s]}catch(l){c={error:l}}finally{try{o&&!o.done&&(u=n.return)&&u.call(n)}finally{if(c)throw c.error}}return[2,null]}}))}))}function Dt(e,t){var n,r;t.isFirebaseMessaging=!0,t.messageType=Xe.PUSH_RECEIVED;try{for(var o=i(e),s=o.next();!s.done;s=o.next()){s.value.postMessage(t)}}catch(a){n={error:a}}finally{try{s&&!s.done&&(r=o.return)&&r.call(o)}finally{if(n)throw n.error}}}function Pt(){return self.clients.matchAll({type:"window",includeUncontrolled:!0})}function Nt(e){var t,n=e.actions,r=Notification.maxActions;return n&&r&&n.length>r&&console.warn("This browser only supports "+r+" actions. The remaining actions will not be displayed."),self.registration.showNotification(null!==(t=e.title)&&void 0!==t?t:"",e)}var At=function(){function e(e){var t=this;this.firebaseDependencies=e,this.vapidKey=null,this.onMessageCallback=null,navigator.serviceWorker.addEventListener("message",(function(e){return t.messageEventListener(e)}))}return Object.defineProperty(e.prototype,"app",{get:function(){return this.firebaseDependencies.app},enumerable:!1,configurable:!0}),e.prototype.messageEventListener=function(e){return n(this,void 0,void 0,(function(){var t,n;return r(this,(function(r){switch(r.label){case 0:return(t=e.data).isFirebaseMessaging?(this.onMessageCallback&&t.messageType===Xe.PUSH_RECEIVED&&("function"==typeof this.onMessageCallback?this.onMessageCallback(function(e){return delete e.messageType,delete e.isFirebaseMessaging,e}
/**
 * @license
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */(Object.assign({},t))):this.onMessageCallback.next(Object.assign({},t))),_t(n=t.data)&&"1"===n["google.c.a.e"]?[4,this.logEvent(t.messageType,n)]:[3,2]):[2];case 1:r.sent(),r.label=2;case 2:return[2]}}))}))},e.prototype.getVapidKey=function(){return this.vapidKey},e.prototype.getSwReg=function(){return this.swRegistration},e.prototype.getToken=function(e){return n(this,void 0,void 0,(function(){return r(this,(function(t){switch(t.label){case 0:return"default"!==Notification.permission?[3,2]:[4,Notification.requestPermission()];case 1:t.sent(),t.label=2;case 2:if("granted"!==Notification.permission)throw et.create("permission-blocked");return[4,this.updateVapidKey(null==e?void 0:e.vapidKey)];case 3:return t.sent(),[4,this.updateSwReg(null==e?void 0:e.serviceWorkerRegistration)];case 4:return t.sent(),[2,mt(this.firebaseDependencies,this.swRegistration,this.vapidKey)]}}))}))},e.prototype.updateVapidKey=function(e){return n(this,void 0,void 0,(function(){return r(this,(function(t){return e?this.vapidKey=e:this.vapidKey||(this.vapidKey=tt),[2]}))}))},e.prototype.updateSwReg=function(e){return n(this,void 0,void 0,(function(){return r(this,(function(t){switch(t.label){case 0:return e||this.swRegistration?[3,2]:[4,this.registerDefaultSw()];case 1:t.sent(),t.label=2;case 2:if(!e&&this.swRegistration)return[2];if(!(e instanceof ServiceWorkerRegistration))throw et.create("invalid-sw-registration");return this.swRegistration=e,[2]}}))}))},e.prototype.registerDefaultSw=function(){return n(this,void 0,void 0,(function(){var e,t;return r(this,(function(n){switch(n.label){case 0:return n.trys.push([0,2,,3]),e=this,[4,navigator.serviceWorker.register("/firebase-messaging-sw.js",{scope:"/firebase-cloud-messaging-push-scope"})];case 1:return e.swRegistration=n.sent(),this.swRegistration.update().catch((function(){})),[3,3];case 2:throw t=n.sent(),et.create("failed-service-worker-registration",{browserErrorMessage:t.message});case 3:return[2]}}))}))},e.prototype.deleteToken=function(){return n(this,void 0,void 0,(function(){return r(this,(function(e){switch(e.label){case 0:return this.swRegistration?[3,2]:[4,this.registerDefaultSw()];case 1:e.sent(),e.label=2;case 2:return[2,wt(this.firebaseDependencies,this.swRegistration)]}}))}))},e.prototype.requestPermission=function(){return n(this,void 0,void 0,(function(){var e;return r(this,(function(t){switch(t.label){case 0:return"granted"===Notification.permission?[2]:[4,Notification.requestPermission()];case 1:if("granted"===(e=t.sent()))return[2];throw"denied"===e?et.create("permission-blocked"):et.create("permission-default")}}))}))},e.prototype.usePublicVapidKey=function(e){if(null!==this.vapidKey)throw et.create("use-vapid-key-after-get-token");if("string"!=typeof e||0===e.length)throw et.create("invalid-vapid-key");this.vapidKey=e},e.prototype.useServiceWorker=function(e){if(!(e instanceof ServiceWorkerRegistration))throw et.create("invalid-sw-registration");if(this.swRegistration)throw et.create("use-sw-after-get-token");this.swRegistration=e},e.prototype.onMessage=function(e){var t=this;return this.onMessageCallback=e,function(){t.onMessageCallback=null}},e.prototype.setBackgroundMessageHandler=function(){throw et.create("only-available-in-sw")},e.prototype.onBackgroundMessage=function(){throw et.create("only-available-in-sw")},e.prototype.onTokenRefresh=function(){return function(){}},e.prototype.logEvent=function(e,t){return n(this,void 0,void 0,(function(){var n;return r(this,(function(r){switch(r.label){case 0:return n=function(e){switch(e){case Xe.NOTIFICATION_CLICKED:return"notification_open";case Xe.PUSH_RECEIVED:return"notification_foreground";default:throw new Error}}(e),[4,this.firebaseDependencies.analyticsProvider.get()];case 1:return r.sent().logEvent(n,{message_id:t["google.c.a.c_id"],message_name:t["google.c.a.c_l"],message_time:t["google.c.a.ts"],message_device_time:Math.floor(Date.now()/1e3)}),[2]}}))}))},e}();function jt(e){return et.create("missing-app-config-values",{valueName:e})}
/**
 * @license
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */var Lt={isSupported:Mt};function Mt(){return self&&"ServiceWorkerGlobalScope"in self?"indexedDB"in self&&null!==indexedDB&&"PushManager"in self&&"Notification"in self&&ServiceWorkerRegistration.prototype.hasOwnProperty("showNotification")&&PushSubscription.prototype.hasOwnProperty("getKey"):"indexedDB"in window&&null!==indexedDB&&navigator.cookieEnabled&&"serviceWorker"in navigator&&"PushManager"in window&&"Notification"in window&&"fetch"in window&&ServiceWorkerRegistration.prototype.hasOwnProperty("showNotification")&&PushSubscription.prototype.hasOwnProperty("getKey")}$.INTERNAL.registerComponent(new m("messaging",(function(e){var t=e.getProvider("app").getImmediate(),n={app:t,appConfig:function(e){var t,n;if(!e||!e.options)throw jt("App Configuration Object");if(!e.name)throw jt("App Name");var r=e.options;try{for(var o=i(["projectId","apiKey","appId","messagingSenderId"]),s=o.next();!s.done;s=o.next()){var a=s.value;if(!r[a])throw jt(a)}}catch(c){t={error:c}}finally{try{s&&!s.done&&(n=o.return)&&n.call(o)}finally{if(t)throw t.error}}return{appName:e.name,projectId:r.projectId,apiKey:r.apiKey,appId:r.appId,senderId:r.messagingSenderId}}(t),installations:e.getProvider("installations").getImmediate(),analyticsProvider:e.getProvider("analytics-internal")};if(!Mt())throw et.create("unsupported-browser");return self&&"ServiceWorkerGlobalScope"in self?new Ct(n):new At(n)}),"PUBLIC").setServiceProps(Lt));export{t as _,n as a,r as b,s as c,$ as f};
