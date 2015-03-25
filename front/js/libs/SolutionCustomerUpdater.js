"use strict";

var ATTRS = {
    COMPANY_NAME: "companyName",
    COMPANY_URL: "companyUrl"
};

export default class SolutionCustomerUpdater {
    constructor() {
        this.$add = $("#add-customer");
        this.$wrapper = $("#customers-wrapper");
        this.$customers = this.$wrapper.find("#customers");
        this.$input = this.$wrapper.find("[name=customer_portfolio]");
        this.$template = this.$wrapper.find(".customer-template > div");

        _.each(this.$customers.data("customers"), this.buildCustomers.bind(this));

        this.$add.click(this.buildCustomers.bind(this));
        this.resetCustomerInput();
    }

    buildCustomers(customer) {
        var $template = this.$template.clone(),
            $input_name = $template.find(".js-customer-name"),
            $input_url = $template.find(".js-customer-url"),
            $delete = $template.find(".js-customer-delete");

        customer = customer || { url: "", name: "" };

        $input_name.val(customer.name);
        $input_name.focusout(this.resetCustomerInput.bind(this));

        $input_url.val(customer.url);
        $input_url.focusout(this.resetCustomerInput.bind(this));

        $delete.click(this.removeCustomer.bind(this, $template));

        this.$customers.append($template);

        return $template;
    }

    removeCustomer($customer_block) {
        $customer_block.remove();
        this.resetCustomerInput();
    }

    resetCustomerInput() {
        var data = [];

        this.$customers.find(".js-customer-block").each((index, block) => {
            var $block = $(block);

            data.push({
                [ATTRS.COMPANY_NAME] : $block.find(".js-customer-name").val(),
                [ATTRS.COMPANY_URL] : $block.find(".js-customer-url").val()
            });
        });

        this.$input.val(JSON.stringify(data));
    }
}