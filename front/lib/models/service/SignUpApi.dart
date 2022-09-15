import 'dart:convert';
import 'dart:io';

import 'package:front/models/core/AccountInput.dart';

import '/utils/Constants.dart' as constants;
import 'package:http/http.dart' as http;

class SignUpApi {
  Future<int> resultSignUp({required AccountInput input}) async {
    Uri url = Uri.parse('${constants.URL}/register');

    http.Response response;
    print(url);
    try {
      response = await http.post(url,
          headers: {'accept': 'application/json'},
          body: jsonEncode(<String, String>{
            "email": input.userName,
            "password": input.password,
            "firstname": input.firstName,
            "lastname": input.lastName
          }));
    } catch (e) {
      print("catch Sign Up");
      return HttpStatus.badRequest;
    }

    print('responseBody Sign Up : ${response.body}');

    if (response.statusCode == HttpStatus.ok) {
      return HttpStatus.ok;
    }

    return HttpStatus.notFound;
  }
}
