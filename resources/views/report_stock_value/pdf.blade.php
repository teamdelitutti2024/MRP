<!DOCTYPE html>
<html>
    <head>
        @include('layouts.elements.pdf_style')
    </head>
    <body>
        @include('layouts.elements.pdf_logo')
        <h2 class="title">{{ __('elemente de Valoración de Inventario') }}</h2>
        <table>
            <thead>
                <tr>
                    <th>{{ __('Categoría') }}</th>
                    <th>{{ __('Clave')}}</th>
                    <th>{{ __('Materia prima') }}</th>
                    <th>{{ __('Costo unitario') }}</th>
                    <th>{{ __('Cantidad') }}</th>
                    <th>{{ __('Unidad de medida') }}</th>
                    <th>{{ __('Costo total') }}</th>
                    <th>{{ __('Ubicación') }}</th> 
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach ($stock as $element)
                    @php $total += $element->average_cost * $element->quantity; @endphp
                    <tr>
                        <td>{{ $element->cat_name }}</td>
                        <td>{{ $element->supply_key }}</td>
                        <td>{{ $element->name }}</a></td>     
                        <td>${{ $element->average_cost }}</a></td>                         
                        <td>{{ $element->quantity }}</td>
                        <td>{{ $element->measure }}</a></td>
                        <td>${{ number_format($element->average_cost * $element->quantity, 2, '.', ',')}}</a></td>        
                        <td>{{ $element->location }}</td>                            
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h3><i class="list-group-item">{{ __('Total') }}: ${{ number_format($total, 2, '.', ',') }}</i></h3>
    </body>
</html>