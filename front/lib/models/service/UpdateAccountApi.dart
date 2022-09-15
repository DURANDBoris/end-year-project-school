import 'dart:convert';
import 'dart:io';

import 'package:dio/dio.dart';
import 'package:front/models/core/AccountInput.dart';

import 'package:front/providers/AuthenticationProvider.dart';
import '/utils/Constants.dart' as constants;

class UpdateAccountApi {
  final Dio dio = Dio();
  Future<int> updateAccount(
      {required AccountInput input,
      required AuthenticationProvider authentication}) async {
    var formJson = jsonEncode(<String, String>{
      "email": input.userName,
      "password": input.password,
      "firstname": input.firstName,
      "lastname": input.lastName
    });
    Response response;
    print('${constants.URL}/user/${authentication.user.id}');
    print("token : ${authentication.user.token}");
    print(formJson);
    try {
      response = await dio.put(
        '${constants.URL}/user/${authentication.user.id}',
        data: formJson,
        options: Options(
          headers: {
            "Authorization": "Bearer ${authentication.user.token}",
            "accept": "application/json"
          },
          //contentType: "application/json",
        ),
      );
    } catch (e) {
      print("catch Update User $e");
      return HttpStatus.badRequest;
    }

    print('responseBody Update User : ${response.data}');

    if (response.statusCode == HttpStatus.ok) {
      authentication.user.email = input.userName;
      authentication.user.firstname = input.firstName;
      authentication.user.lastName = input.lastName;
      return HttpStatus.ok;
    }

    return HttpStatus.notFound;
  }
}
