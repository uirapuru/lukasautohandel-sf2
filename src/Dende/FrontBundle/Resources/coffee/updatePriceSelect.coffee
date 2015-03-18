(($, window) ->
  class updatePriceSelect
    $form: null

    constructor: (el, options) ->
      @$form = $(el)

      parentSelector = "ul.collection-container > li > div.form-group > div.input-group"
      linkSelector =  "#{parentSelector} a.changeCurrencyLink"

      @$form.on 'click.updatePriceSelect', linkSelector, (e) =>
        $item = $(e.target)
        $parent = $item.parents(parentSelector)
        $select = $parent.find 'select[id^="dende_form_car_prices_"]'

        if $select.length == 0
          throw "Widget select#dende_form_car_prices_ bounded to price field not found"

        $select.val $item.data("id")

        $item.parents("div.input-group-btn").find("button.currencySymbol").text($item.data("symbol"))

  $.fn.extend updatePriceSelect: (option, args...) ->
    @each ->
      $this = $(this)
      data = $this.data('updatePriceSelect')

      if !data
        $this.data 'updatePriceSelect', (data = new updatePriceSelect(this, option))
      if typeof option == 'string'
        data[option].apply(data, args)

) window.jQuery, window
