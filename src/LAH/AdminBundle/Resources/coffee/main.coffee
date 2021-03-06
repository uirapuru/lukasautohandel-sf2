$ ->

  $("[rel^='prettyPhoto']").prettyPhoto
    social_tools: null
    show_title: false

# UPDATE PRICE SELECT

  $('div#lah_car_prices').updatePriceSelect()

# COLLECTIONS

  $("div#lah_car_images").collection
    dataField : "image-prototype"
    formSelector : "form[name='lah_car']"
    regex: /__image_name__/g

  $("div#lah_car_prices").collection
    dataField : "price-prototype"
    formSelector : "form[name='lah_car']"
    regex: /__price_name__/g

# TOGGLE WIDGETS

  $("a#add-new-car-type").toggleWidgets
    normal : "div#car_type_row"
    toggled : "div#car_add_type_row"
    close : "a#add-new-car-type-close"

  $("a#add-new-car-model").toggleWidgets
    normal : "div#car_model_row"
    toggled : "div#car_add_model_row"
    close : "a#add-new-car-model-close"

  $("a#add-new-car-color").toggleWidgets
    normal : "div#car_color_row"
    toggled : "div#car_add_color_row"
    close : "a#add-new-car-color-close"

# delete form
  $("form[id^='delete_']").off('click.delete_car').on 'click.delete_car', (event) =>
    $form = $(event.target).parents('form')

    if confirm $form.data 'confirm-text'
      $form.submit()

  $("ol.sortable").sortable
    forcePlaceholderSize: true
    placeholder: "ui-state-highlight"
    update: (event, ui) ->
      inputs = ui.item.parents("ol").find("input:hidden[name$='[position]']")
      $.each inputs, (i, el) =>
        element = "##{$(el).attr('id')}"
        $(element).val(i)

  $("ol.sortable").disableSelection()

  $("ol.sortable").off('change.upload_file').on 'change.upload_file', "input:file", (e) ->
    file = e.target.files[0]
    reader = new FileReader();
    $targetImg = $(e.target).parents('li').find('img.image-collection-item.img-thumbnail')

    reader.onload = (e) =>
      $targetImg.attr('src', e.target.result)

    reader.readAsDataURL(file);