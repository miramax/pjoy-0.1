/*
 * jQuery Russian Translit
 * Copyright 2012 Gradusov Andy
 * Released under the MIT and GPL licenses.
 */
(function($){
  $.fn.Translit = function(options) {

    var options = $.extend({

      input: false,

      output: false,

      prefix: '',

      spaces: ' ',

      events: ['keyup', 'change'],

      rus: ["\u0410", "\u0430", "\u0411", "\u0431", "\u0412", "\u0432", "\u0413", "\u0433", "\u0414", "\u0434",
      "\u0415", "\u0435", "\u0401", "\u0451", "\u0416" ,"\u0436", "\u0417", "\u0437", "\u0418", "\u0438",
      "\u0419", "\u0439", "\u041a", "\u043a", "\u041b", "\u043b", "\u041c", "\u043c", "\u041d", "\u043d",
      "\u041e", "\u043e", "\u041f", "\u043f", "\u0420", "\u0440", "\u0421", "\u0441", "\u0422", "\u0442",
      "\u0423", "\u0443", "\u0424", "\u0444", "\u0425", "\u0445", "\u0426", "\u0446", "\u0427", "\u0447",
      "\u0428", "\u0448", "\u0429", "\u0449", "\u042a", "\u044a", "\u042b", "\u044b", "\u042c", "\u044c",
      "\u042d", "\u044d", "\u042e", "\u044e", "\u042f", "\u044f", " "],

      eng: ["a", "a", "b", "b", "v", "v", "g", "g", "d", "d",
      "e", "e", "e", "e", "zh", "zh", "z", "z", "i", "i",
      "y", "y", "k", "k", "l", "l", "m", "m", "n", "n",
      "o", "o", "p", "p", "r", "r", "s", "s", "t", "t",
      "u", "u", "ph", "f", "h", "h", "c", "c", "ch", "ch",
      "sh", "sh", "sh", "sh", "", "", "i", "i", "\'", "\'",
      "e", "e", "yu", "yu", "ya", "ya"],

      contains: !!true

    }, options||{});

    options.eng.push(options.spaces);

    if(options.input && options.output) {
      options.i = this.find(options.input);
      options.o = this.find(options.output);
    } else {
      return;
    }

    function replacing() {
      options.english_words = options.i.val().split('');
      options.russian_words = [];

      for (var en_key in options.english_words) {

        options.contains = false;

        for(var ru_key in options.rus) {
          if(options.english_words[en_key] == options.rus[ru_key]) {
            options.russian_words.push(options.eng[ru_key]);
            options.contains = true;
          }
        }

        if(!options.contains) {
          options.russian_words.push(options.english_words[en_key]);
        }
      }

      options.o.val(options.russian_words.join('') + options.prefix);
    };

    for(var e in options.events) {
      options.i.live( options.events[e] , function(){
        replacing();
      });
    }

    return this;
  };
})(jQuery);