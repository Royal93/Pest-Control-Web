<?php

namespace App\Http\Requests;

use App\Models\Sale;
use App\Rules\SouthAfricanPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** The Capture a Sale questionnaire, shared by the admin portal and SP SALES. */
class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // route middleware already decides who can get here
    }

    public function rules(): array
    {
        $rules = [
            'lead_id' => ['nullable', 'integer', 'exists:leads,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', new SouthAfricanPhone],
            'email' => ['required', 'email:rfc', 'max:255'],
            'service_address' => ['required', 'string', 'max:255'],
            'suburb' => ['required', 'string', 'max:120'],
            'property_type' => ['required', Rule::in(array_keys(Sale::PROPERTY_TYPES))],
            'service' => ['required', Rule::in(array_keys(Sale::SERVICES))],
            'children_or_pets' => ['required', Rule::in(['yes', 'no'])],
            'bait_stations' => ['nullable', 'required_if:service,rodent,both', 'integer', 'min:1', 'max:200'],
            'treatment_areas' => ['nullable', 'required_if:service,cockroach,both', 'string', 'max:1000'],
            'agreement_date' => ['required', 'date'],
            'inception_date' => ['required', 'date', 'after_or_equal:agreement_date'],
            'activation_fee' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'monthly_subscription' => ['required', 'numeric', 'min:5', 'max:100000'],
            'payment_frequency' => ['required', Rule::in(array_keys(Sale::FREQUENCIES))],
            'explained' => ['accepted'],
        ];

        if ($this->routeIs('admin.*')) {
            $rules['sales_rep_id'] = ['nullable', 'integer', Rule::exists('users', 'id')->where('role', 'marketing')];
        } else {
            // Reps only ever capture a sale from a lead assigned to them.
            $rules['lead_id'] = ['required', 'integer', 'exists:leads,id'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'bait_stations.required_if' => 'Please enter the number of proposed bait stations for rodent control.',
            'treatment_areas.required_if' => 'Please describe the treatment areas for cockroach control.',
            'inception_date.after_or_equal' => 'The inception contract date cannot be before the agreement date.',
            'monthly_subscription.min' => 'The monthly subscription must be at least R5.00.',
            'explained.accepted' => 'Please confirm the service and charges were explained to the customer.',
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_name' => 'customer / business',
            'service_address' => 'service address',
            'property_type' => 'property',
            'service' => 'service selected',
            'children_or_pets' => 'children or pets on premises',
            'inception_date' => 'inception contract date',
            'activation_fee' => 'activation or installation fee',
            'monthly_subscription' => 'total monthly subscription',
            'sales_rep_id' => 'sales representative',
        ];
    }
}
