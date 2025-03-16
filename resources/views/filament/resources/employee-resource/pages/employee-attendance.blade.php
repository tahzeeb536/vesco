<x-filament-panels::page>
    <x-slot name="headerActions">
        @foreach ($this->getHeaderActions() as $action)
            {{ $action }}
        @endforeach
    </x-slot>    
<style>
    .table-wrapper {
        overflow-x: scroll;
    }

    table {
        font-size: 14px;
    }

    table td, table th {
        border: 1px solid gray;
        padding: 5px;
    }

</style>
<div class="table-wrapper">
    <form action=""></form>
    <table class="table table-bordered table-striped" style>
        <tbody>
            <tr>
                <th>Days</th>
                <th class="text-center">Sat<br>1</th>
                <th class="text-center">Sun<br>2</th>
                <th class="text-center">Mon<br>3</th>
                <th class="text-center">Tue<br>4</th>
                <th class="text-center">Wed<br>5</th>
                <th class="text-center">Thu<br>6</th>
                <th class="text-center">Fri<br>7</th>
                <th class="text-center">Sat<br>8</th>
                <th class="text-center">Sun<br>9</th>
                <th class="text-center">Mon<br>10</th>
                <th class="text-center">Tue<br>11</th>
                <th class="text-center">Wed<br>12</th>
                <th class="text-center">Thu<br>13</th>
                <th class="text-center">Fri<br>14</th>
                <th class="text-center">Sat<br>15</th>
                <th class="text-center">Sun<br>16</th>
                <th class="text-center">Mon<br>17</th>
                <th class="text-center">Tue<br>18</th>
                <th class="text-center">Wed<br>19</th>
                <th class="text-center">Thu<br>20</th>
                <th class="text-center">Fri<br>21</th>
                <th class="text-center">Sat<br>22</th>
                <th class="text-center">Sun<br>23</th>
                <th class="text-center">Mon<br>24</th>
                <th class="text-center">Tue<br>25</th>
                <th class="text-center">Wed<br>26</th>
                <th class="text-center">Thu<br>27</th>
                <th class="text-center">Fri<br>28</th>
                <th class="text-center">Sat<br>29</th>
                <th class="text-center">Sun<br>30</th>
                <th class="text-center">Mon<br>31</th>
            </tr>
            <tr>
                <td><strong>Status</strong></td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
            </tr>
            <tr>
                <td><strong>Hours</strong></td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
            </tr>
            <tr>
                <td><strong>Late</strong></td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
            </tr>
            <tr>
                <td><strong>O.T</strong></td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
            </tr>
            <tr>
                <td colspan="32">
                    <strong>Absents:</strong> 0 <strong>Leaves:</strong> 0 <strong>Presents:</strong>31 <strong>Total
                        Hours:</strong>0 <strong>Overtime:</strong>0
                    <strong>Total Late Hours: 0</strong>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</x-filament-panels::page>
