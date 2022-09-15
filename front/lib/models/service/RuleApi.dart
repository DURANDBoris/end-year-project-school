import 'dart:convert';
import 'dart:io';

import 'package:front/models/core/Rule.dart';
import 'package:front/providers/AuthenticationProvider.dart';
import 'package:dio/dio.dart';
import '/utils/Constants.dart' as constants;

class RuleApi {
  final Dio dio = Dio();

  Future<List<Rule>?> getFolderRule(
      {required int idFolder,
      required AuthenticationProvider authentication}) async {
    print('getFolderRule');
    print('${constants.URL}/rules/$idFolder');
    Response response;
    try {
      response = await dio.get(
        '${constants.URL}/rules/$idFolder',
        options: Options(
          headers: {'Authorization': "Bearer ${authentication.user.token}"},
          contentType: "application/form-data",
        ),
      );
    } catch (e) {
      print("catch getFolderRule $e");
      return null;
    }
    print('responseBody getFolderRule: ${response.data}');

    if (response.statusCode == HttpStatus.ok) {
      List<Rule> listRule = [];
      Map<String, dynamic> json = response.data; //jsonDecode(response.data);
      Iterable iterable = json['list'];
      listRule = iterable.map((i) => Rule.fromJson(i)).toList();
      // Folder folder = Folder.fromJson(json);

      return listRule;
    }

    return null;
  }

  Future<Rule?> createFolderRule(
      {required int idFolder,
      required AuthenticationProvider authentication,
      required Rule rule}) async {
    print('createFolderRule');
    print('${constants.URL}/rule/$idFolder');
    Response response;
    var formJson = jsonEncode(<String, dynamic>{
      "name": rule.name,
      "type": rule.type,
      "isMandatory": rule.mandatory,
    });
    print(formJson);
    try {
      response = await dio.post('${constants.URL}/rule/$idFolder',
          options: Options(
            headers: {'Authorization': "Bearer ${authentication.user.token}"},
            contentType: "application/form-data",
          ),
          data: formJson);
    } catch (e) {
      print("catch createFolderRule $e");
      return null;
    }
    print('responseBody createFolderRule: ${response.data}');

    if (response.statusCode == HttpStatus.ok) {
      Map<String, dynamic> json = response.data; //jsonDecode(response.data);
      Rule rule = Rule.fromJson(json);

      return rule;
    }

    return null;
  }

  Future<Rule?> updateFolderRule(
      {required int idFolder,
      required AuthenticationProvider authentication,
      required Rule rule}) async {
    print('updateFolderRule');
    print('${constants.URL}/rule/${rule.id}');
    Response response;
    var formJson = jsonEncode(<String, dynamic>{
      "name": rule.name,
      "type": rule.type,
      "isMandatory": rule.mandatory,
    });
    print(formJson);
    try {
      response = await dio.put('${constants.URL}/rule/${rule.id}',
          options: Options(
            headers: {'Authorization': "Bearer ${authentication.user.token}"},
            contentType: "application/form-data",
          ),
          data: formJson);
    } catch (e) {
      print("catch updateFolderRule $e");
      return null;
    }
    print('responseBody updateFolderRule: ${response.data}');

    if (response.statusCode == HttpStatus.ok) {
      Map<String, dynamic> json = response.data; //jsonDecode(response.data);
      Rule rule = Rule.fromJson(json);

      return rule;
    }

    return null;
  }

  Future<bool> deleteRule(
      {required int idRule,
      required AuthenticationProvider authentication}) async {
    print('deleteRule');
    print('${constants.URL}/rules/$idRule');
    Response response;
    try {
      response = await dio.delete(
        '${constants.URL}/rule/$idRule',
        options: Options(
          headers: {'Authorization': "Bearer ${authentication.user.token}"},
          contentType: "application/form-data",
        ),
      );
    } catch (e) {
      print("catch deleteRule $e");
      return false;
    }
    print('responseBody deleteRule: ${response.data}');

    if (response.statusCode == HttpStatus.ok) {
      return true;
    }

    return false;
  }
}
