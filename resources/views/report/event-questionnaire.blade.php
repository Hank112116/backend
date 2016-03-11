<table class="table table-striped">
    @foreach($questionnaire_items as $key => $item)
    <tr>
        <td>{{ $questionnaire_column[$key] }}</td>
        <td>{{ $item }}</td>
    </tr>
    @endforeach
</table>