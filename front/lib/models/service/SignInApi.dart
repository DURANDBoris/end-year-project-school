import 'dart:convert';
import 'dart:io';
import 'package:dio/dio.dart';
import 'package:front/models/core/User.dart';

import '/utils/Constants.dart' as constants;

class SignInApi {
  final Dio dio = Dio();
  Future<User?> login({required username, required password}) async {
    var formJson = jsonEncode(
      <String, String>{
        "username": username,
        "password": password,
      },
    );
    print('${constants.URL}/login');
    Response response;
    try {
      response = await dio.post(
        '${constants.URL}/login',
        data: formJson,
        options: Options(
          contentType: "application/json",
        ),
      );
    } catch (e) {
      print("catch login $e");
      return null;
    }
    print('responseBody login: ${response.data}');

    if (response.statusCode == HttpStatus.ok) {
      Map<String, dynamic> json = response.data; //jsonDecode(response.data);
      User user = User.fromJson(json);
      print("token : ${user.token}");
      print("id : ${user.id}");
      return user;
    }

    return null;
  }
}

//Future<User?> login(
//     {required String username, required String password}) async {
//   print("username : $username,password:$password");
//   Uri url = Uri.parse('${constants.URL}/login');
//
//   http.Response response;
//   print(url);
//   try {
//     response = await http.post(url,
//         headers: {'accept': 'application/json'},
//         body: jsonEncode(
//             <String, String>{"username": username, "password": password}));
//   } catch (e) {
//     print('catch login');
//     return null;
//   }
//
//   print('responseBody login: ${response.body}');
//
//   if (response.statusCode == HttpStatus.ok) {
//     Map<String, dynamic> json = jsonDecode(response.body);
//
//     User user = User.fromJson(json);
//     return user;
//   }
//
//   return null;
// }
