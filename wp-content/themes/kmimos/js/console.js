(function(window, document) {

    // Create the DOM structure to hold the console messages

    var div = document.createElement("div");
    div.style.cssText = "position: fixed; " +
    "bottom: 5px; left: 5px; right: 5px; bottom: 5px; " +
    "padding: 10px; " +
    "overflow-y: auto; " + 
    "display: none; " + 
    "background: rgba(0, 32, 0, 0.9); " +
    "border: 3px solid #888; " + 
    "font: 14px Consolas,Monaco,Monospace; " +
    "color: #ddd; " + 
    "z-index: 99999999999999999; height: calc( 50% - 10px );";

    /*
        position: fixed;
        left: 5px;
        right: 5px;
        bottom: 5px;
        padding: 10px;
        overflow-y: auto;
        display: block;
        background: rgba(0, 32, 0, 0.9);
        border: 3px solid rgb(136, 136, 136);
        font-style: normal;
        font-variant: normal;
        font-weight: normal;
        font-stretch: normal;
        font-size: 14px;
        line-height: normal;
        font-family: Consolas, Monaco, monospace;
        color: rgb(221, 221, 221);
        height: calc( 100% - 10px );
        z-index: 9999999999;
    */

    var ul = document.createElement("ul");
    ul.style.cssText = "padding: 0; list-style-type: none; margin: 0";
    div.appendChild(ul)

    document.body.appendChild(div);

    var toggleButton = document.createElement("button");
    toggleButton.innerText = "Console";
    toggleButton.style.cssText = "position: fixed; right: 10px; top: 10px; z-index: 999999999999999999";

    toggleButton.addEventListener("click", function () {
        div.style.display = div.style.display === "none" ? "block" : "none";
    });

    document.body.appendChild(toggleButton);
  
    var clearButton = document.createElement("button");
    clearButton.innerText = "Clear";
    clearButton.style.cssText = "position: fixed; right: 10px; top: 30px; z-index: 999999999999999999";
    
    clearButton.addEventListener("click", function() {
        ul.innerHTML = "";
    });
    
    div.appendChild(clearButton);
  
    function addMsg(msg) {
        var li = document.createElement("li");
        li.innerText = msg;
        ul.appendChild(li);
    }

    // Monkey-patch console object

    var methods = ["log", "debug", "error", "info", "warn"];

    for (var i = 0; i < methods.length; i++) {
        var method = methods[i];
        var original = window.console[method];
        window.console[method] = function(msg) {
            addMsg(msg);
            original.apply(window.console, arguments);
        };
    }

})(window, document);