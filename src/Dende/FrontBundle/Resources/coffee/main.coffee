$ ->
  $("div#dende_form_car_images").collection
    dataField : "image-prototype"
    formSelector : "form[name='dende_form_car']"

  $("div#dende_form_car_prices").collection
    dataField : "price-prototype"
    formSelector : "form[name='dende_form_car']"

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