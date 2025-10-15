<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarClasificacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $libro = $this->input('ID_libro');
        $parte = $this->input('ID_parte');
        $tema  = $this->input('ID_tema');

        return [
            'ID_libro'  => ['required','integer', Rule::exists('libro','ID_libro')],
            'ID_parte'  => ['required','integer',
                Rule::exists('parte','ID_parte')->where(fn($q) => $q->where('ID_libro', $libro))
            ],
            'ID_tema'   => ['required','integer',
                Rule::exists('tema','ID_tema')->where(fn($q) => $q->where('ID_parte', $parte))
            ],
            'ID_titulo' => ['required','integer',
                Rule::exists('titulo','ID_titulo')->where(fn($q) => $q->where('ID_tema', $tema))
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'ID_libro'  => 'libro',
            'ID_parte'  => 'parte',
            'ID_tema'   => 'tema',
            'ID_titulo' => 'título',
        ];
    }

    public function messages(): array
    {
        return [
            'ID_parte.exists'  => 'La parte no pertenece al libro seleccionado.',
            'ID_tema.exists'   => 'El tema no pertenece a la parte seleccionada.',
            'ID_titulo.exists' => 'El título no pertenece al tema seleccionado.',
        ];
    }
}
